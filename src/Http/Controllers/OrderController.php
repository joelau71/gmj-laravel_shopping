<?php

namespace GMJ\LaravelShopping\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Setting;
use Gloudemans\Shoppingcart\Facades\Cart;
use GMJ\LaravelShopping\Models\Order;
use GMJ\LaravelShopping\Models\Product;
use GMJ\LaravelShopping\Services\CheckoutService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use PayPal;

class OrderController extends Controller
{
    public function checkout()
    {

        if (!Cart::count()) {
            return back();
        }

        $collections = Cart::content();

        $setting = Setting::first();
        $copyright = Page::where("slug", "copyright")->first();
        $disclaimer = Page::where("slug", "disclaimer")->first();
        $terms = Page::where("slug", "terms")->first();

        return view("LaravelShoppingOrder::checkout", compact("collections", "setting", "copyright", "disclaimer", "terms"));
    }

    public function checkout2()
    {
        request()->validate([
            "shipping_fullname" => ["required", "max:200"],
            "shipping_address" => ["required", "max:600"],
            "shipping_phone" => ["required", "integer", "digits_between:8,12"],
            "billing_fullname" => ["required", "max:200"],
            "billing_address" => ["required", "max:600"],
            "billing_phone" => ["required", "integer", "digits_between:8,12"],
            "notes" => "max:1000",
            "payment_method" => ["required", Rule::in(["cash_on_delivery", "paypal", "stripe"])],
        ], [
            "shipping_fullname.required" => trans("LaravelShopping::web.shipping_fulllname.required"),
            "shipping_fullname.max" => trans("LaravelShopping::web.shipping_fulllname.max"),
            "shipping_address.required" => trans("LaravelShopping::web.shipping_address.required"),
            "shipping_address.max" => trans("LaravelShopping::web.shipping_address.max"),
            "shipping_phone.required" => trans("LaravelShopping::web.shipping_phone.required"),
            "shipping_phone.integer" => trans("LaravelShopping::web.shipping_phone.integer"),
            "shipping_phone.digits_between" => trans("LaravelShopping::web.shipping_phone.digits_between"),
        ]);

        $cs = new CheckoutService;
        $result = $cs->validateCartProductIsset()->validateProductStoring();

        if ($result->errors) {
            alert()->error($result->errors);
            return back();
        }


        try {
            $total = (int) Cart::priceTotal();

            $order = Order::create([
                "user_id" => auth()->id(),
                "total" => $total,
                "payment_method" => request()->payment_method,
                "shipping_fullname" => request()->shipping_fullname,
                "shipping_address" => request()->shipping_address,
                "shipping_phone" => request()->shipping_phone,
                "billing_fullname" => request()->billing_fullname,
                "billing_address" => request()->billing_address,
                "billing_phone" => request()->billing_phone,
                "notes" => request()->notes,
            ]);
            if ($order->payment_method == "paypal") {
                PayPal::setProvider();
                $provider = PayPal::getProvider();
                $provider->setApiCredentials(config('paypal'));
                $provider->setAccessToken($provider->getAccessToken());

                $response = $provider->createOrder([
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'reference_id' => uniqid(),
                        'amount' => [
                            'currency_code' => 'HKD',
                            'value' => $total,
                        ]
                    ]],
                    'application_context' => [
                        "return_url" => route("LaravelShopping.order.success"),
                        "cancel_url" => route("LaravelShopping.order.cancel"),
                    ]
                ]);

                $order->payment_ref_id = $response['id'];
                $order->save();
                $link = $response["links"][1]["href"];
                return redirect($link)->send();
            }

            if ($order->payment_method == "stripe") {
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                $response = $stripe->checkout->sessions->create([
                    'success_url' => route("LaravelShopping.order.success"),
                    'cancel_url' => route("LaravelShopping.order.cancel"),
                    'line_items' => [
                        [
                            'quantity' => 1,
                            'currency' => 'HKD',
                            'amount' => $total * 100,
                            'name' => 'test',
                        ],
                    ],
                    'payment_method_types' => ['card'],
                    'mode' => 'payment',
                ]);

                $order->payment_ref_id = $response->id;
                $order->save();

                return redirect($response->url)->withStatus(303);
            }

            if ($order->payment_method == "cash_on_delivery") {
                foreach (Cart::content() as $item) {
                    Product::find($item->id)->decrement("quantity", $item->qty);
                }
                Cart::destroy();
            }
        } catch (\Exception $e) {
            abort(500);
        }
    }

    public function success()
    {
        try {
            $order = Order::where("user_id", auth()->id())->orderBy("id", "DESC")->first();

            if ($order->payment_method == "paypal") {
                $provider = PayPal::setProvider();
                $provider->setApiCredentials(config('paypal'));
                $token = $provider->getAccessToken();
                $provider->setAccessToken($token);
                $response = $provider->capturePaymentOrder($order->payment_ref_id);
                if ($response["status"] == "COMPLETED") {
                    $this->successPayment();
                    alert()->success('Success', 'Payment Confirmed!');
                    return redirect()->route("frontend.page", ["slug" => "home"]);
                }
            }

            if ($order->payment_method == "stripe") {
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                $res = $stripe->checkout->sessions->retrieve($order->payment_ref_id, []);
                if ($res->payment_status == "paid") {
                    $this->successPayment();
                    alert()->success('Success', 'Payment Confirmed!');
                    return redirect()->route("frontend.page", ["slug" => "home"]);
                }
            }

            alert()->error("Error", "Payment Fail!");
            return redirect()->route("frontend.page", ["slug" => "home"]);
        } catch (\Exception $e) {
            abort(500);
        }
    }

    private function successPayment()
    {
        $order = Order::where("user_id", auth()->id())->orderBy("id", "DESC")->first();

        foreach (Cart::content() as $item) {
            Product::find($item->id)->decrement("quantity", $item->qty);
        }

        Cart::destroy();
        $order->is_paid = 1;
        $order->status = "completed";

        $order->save();
    }

    public function cancel()
    {
        dd("payment fail");
    }
}

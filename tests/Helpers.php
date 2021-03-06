<?php

namespace Tests;

use App\Models\Admin;
use App\Models\Ban;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\UserLocation;
use App\Models\UserPersonal;
use App\Models\WarehouseItem;
use Illuminate\Http\UploadedFile;

trait Helpers
{

    private $currentUser;
    private $currentAdmin;

    public function createUser($userData = [], $data = [])
    {
        $this->currentUser = factory(User::class)->create($userData);

        $userInfoData = array();

        if (isset($data['info'])) {
            $userInfoData = $data['info'];
        }

        $userLocationData = array();

        if (isset($data['location'])) {
            $userLocationData = $data['location'];
        }

        $userPersonalData = array();

        if (isset($data['personal'])) {
            $userPersonalData = $data['personal'];
        }

        $userInfoData['user_id']     = $this->currentUser->id;
        $userLocationData['user_id'] = $this->currentUser->id;
        $userPersonalData['user_id'] = $this->currentUser->id;

        $user_info     = factory(UserInfo::class)->create($userInfoData);
        $user_location = factory(UserLocation::class)->create($userLocationData);
        $user_personal = factory(UserPersonal::class)->create($userPersonalData);
    }

    public function actingAsUser()
    {

        if ($this->currentUser == null || User::count() == 0) {
            $this->createUser();
        }

        $this->actingAs($this->currentUser);
    }

    public function actingAsAdmin()
    {
        $this->currentAdmin = factory(Admin::class)->create();

        $this->actingAs($this->currentAdmin, 'admin');
    }

    public function banUser()
    {

        if ($this->currentUser == null) {
            $this->createUser();
        }

        return factory(Ban::class)->create(['user_id' => $this->currentUser->id]);
    }

    public function addProductToUserShoppingCart($amountOfProducts = 2, $addItemToWarehouse = true)
    {

        if ($this->currentUser == null) {
            $this->createUser();
        }

        for ($i = 0; $i < $amountOfProducts; $i++) {
            $product = factory(Product::class)->create();

            if ($addItemToWarehouse) {
                $item = factory(WarehouseItem::class)->create(['product_id' => $product->id]);
            }

            $this->post('/systemUser/addToShoppingCart', ['id' => $product->id])->assertJsonStructure();
        }

    }

    public function confirmShoppingCart()
    {
        $response = $this->post('/systemUser/confirmShoppingCart', [])->assertJsonStructure();
        $result   = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail("Error while confirming shopping cart!");
        }

    }

    public function productUploadImages()
    {
        $images = array();

        $file1 = __DIR__ . "/TestData/photo_1.jpg";
        array_push($images, new UploadedFile($file1, 'photo_1.jpg', filesize($file1), null, null, true));

        $file2 = __DIR__ . "/TestData/photo_2.png";
        array_push($images, new UploadedFile($file2, 'photo_2.png', filesize($file2), null, null, true));

        $response = $this->post('/systemAdmin/productAddImageUpload', [
            'images' => $images,
        ])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg'] ?? "Failed to upload images to product!");
        }

    }

    public function productUploadedImagesList()
    {
        $response = $this->post('/systemAdmin/productLoadOldImages', [])->assertJsonStructure();

        $result = json_decode($response->getContent(), true);

        if (!$result['success']) {
            $this->fail($result['msg'] ?? "Failed to load images!");
        }

        return $result['images'];
    }

    public function createOrder($products = 1, $itemsPerProduct = 2)
    {

        $category = factory(Category::class)->create();

        if ($this->currentUser == null) {
            $this->createUser();
        }

        $order = factory(Order::class)->create(['user_id' => $this->currentUser->id, 'status' => 'REALIZE']);

        for ($i = 0; $i < $products; $i++) {
            $product = factory(Product::class)->create(['category_id' => $category->id, 'status' => 'ACTIVE']);

            for ($i = 0; $i < $itemsPerProduct; $i++) {
                $item = factory(WarehouseItem::class)->create(['product_id' => $product->id]);

                $order_product = factory(OrderProduct::class)->create([
                    'order_id'          => $order->id,
                    'product_id'        => $product->id,
                    'warehouse_item_id' => $item->id,
                    'price'             => $product->priceCurrent,
                    'name'              => $product->title,
                ]);
            }

        }

        return $order;
    }

    public function createProductWithOrder($orders = 1, $items = 1)
    {
        $category = factory(Category::class)->create();
        $product  = factory(Product::class)->create(['category_id' => $category->id, 'status' => 'ACTIVE']);

        if ($this->currentUser == null) {
            $this->createUser();
        }

        for ($o = 0; $o < $orders; $o++) {
            $order = factory(Order::class)->create(['user_id' => $this->currentUser->id, 'status' => 'REALIZE']);

            for ($i = 0; $i < $items; $i++) {
                $item = factory(WarehouseItem::class)->create(['product_id' => $product->id]);

                $order_product = factory(OrderProduct::class)->create([
                    'order_id'          => $order->id,
                    'product_id'        => $product->id,
                    'warehouse_item_id' => $item->id,
                    'price'             => $product->priceCurrent,
                    'name'              => $product->title,
                ]);
            }

        }

        return $product;
    }

    public function createProduct($withImages = true, $items = 1)
    {
        $category = factory(Category::class)->create();

        $product = factory(Product::class)->create(['category_id' => $category->id, 'status' => 'ACTIVE']);

        for ($i = 0; $i < $items; $i++) {
            $item = factory(WarehouseItem::class)->create(['product_id' => $product->id]);
        }

        if ($withImages) {
            for ($i = 0; $i < 2; $i++) {
                $image = factory(ProductImage::class)->create(['product_id' => $product->id]);
            }
        }

        return $product;
    }

    public function getSignUpData()
    {
        $res = [
            'email'      => $this->faker->unique()->safeEmail,
            'pass'       => 'password',
            'grecaptcha' => 'PHP_UNIT_TEST_API_DSAIUBAVS9A47TGB47A804GBS',

            'firstname'  => $this->faker->firstName,
            'surname'    => $this->faker->lastName,
            'phone'      => $this->faker->numberBetween(111111111, 999999999),

            'district'   => $this->faker->numberBetween(1, 16),
            'city'       => $this->faker->city,
            'zipcode'    => $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999),
            'address'    => $this->faker->streetName,
        ];
        return $res;
    }

    public function getOrderCreateData($locker = false)
    {
        $res = [
            'paymentType' => "PAYPAL",
            'clientFName' => $this->faker->firstName,
            'clientSName' => $this->faker->lastName,
            'clientPhone' => $this->faker->numberBetween(111111111, 999999999),
            'note'        => $this->faker->text(150),
        ];

        if ($locker) {
            $res['deliver'] = [
                'type'       => "INPOST_LOCKER",
                'lockerName' => "GWI03L",
            ];
        } else {
            $res['deliver'] = [
                'type'     => "COURIER",
                'district' => $this->faker->numberBetween(1, 16),
                'city'     => $this->faker->city,
                'zipcode'  => $this->faker->numberBetween(10, 99) . '-' . $this->faker->numberBetween(100, 999),
                'address'  => $this->faker->streetName,
            ];
        }

        return $res;
    }

}

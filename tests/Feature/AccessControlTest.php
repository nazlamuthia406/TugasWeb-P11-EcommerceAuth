<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Versi otomatis dari "testing incognito 2 role": php artisan test --filter=AccessControlTest */
class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/manage/products')->assertRedirect('/login');
        $this->get('/cart')->assertRedirect('/login');
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_public_pages_are_open_to_guests(): void
    {
        $product = Product::factory()->create();

        $this->get('/')->assertOk();
        $this->get('/products')->assertOk();
        $this->get(route('products.show', $product))->assertOk();
    }

    public function test_only_admin_can_open_admin_panel(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
        $this->actingAs(User::factory()->editor()->create())->get('/admin')->assertForbidden();
        $this->actingAs(User::factory()->admin()->create())->get('/admin')->assertOk();
    }

    public function test_user_can_edit_own_product_but_not_others(): void
    {
        $owner   = User::factory()->create();
        $other   = User::factory()->create();
        $product = Product::factory()->for($owner, 'user')->create();

        $this->actingAs($owner)->get(route('products.edit', $product))->assertOk();
        $this->actingAs($other)->get(route('products.edit', $product))->assertForbidden();
        $this->actingAs($other)->delete(route('products.destroy', $product))->assertForbidden();
    }

    public function test_editor_can_edit_but_not_delete_others_products(): void
    {
        $editor  = User::factory()->editor()->create();
        $product = Product::factory()->create();

        $this->actingAs($editor)->get(route('products.edit', $product))->assertOk();
        $this->actingAs($editor)->delete(route('products.destroy', $product))->assertForbidden();
        $this->assertModelExists($product);
    }

    public function test_admin_can_delete_any_product(): void
    {
        $admin   = User::factory()->admin()->create();
        $product = Product::factory()->create();

        $this->actingAs($admin)->delete(route('products.destroy', $product))->assertRedirect();
        $this->assertModelMissing($product);
    }

    public function test_role_cannot_be_injected_through_registration(): void
    {
        $this->post('/register', [
            'name' => 'Penyusup', 'email' => 'x@example.com',
            'password' => 'password', 'password_confirmation' => 'password',
            'role' => 'admin',
        ]);

        $this->assertSame('user', User::where('email', 'x@example.com')->value('role'));
    }
}

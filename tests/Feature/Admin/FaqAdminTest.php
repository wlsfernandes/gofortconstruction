<?php

namespace Tests\Feature\Admin;

use App\Enums\FaqCategory;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->admin()->create());
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'question' => 'Do you pull permits?',
            'answer' => 'Yes, we handle every permit.',
            'category' => 'process',
            'sort_order' => '3',
            'is_featured' => '0',
        ], $overrides);
    }

    public function test_index_lists_faqs_in_sort_order(): void
    {
        Faq::factory()->create(['question' => 'Second question?', 'sort_order' => 2]);
        Faq::factory()->create(['question' => 'First question?', 'sort_order' => 1, 'category' => FaqCategory::Warranty]);

        $this->get('/admin/faqs')
            ->assertOk()
            ->assertSeeInOrder(['First question?', 'Second question?'])
            ->assertSee('Warranty');
    }

    public function test_create_form_renders(): void
    {
        $this->get('/admin/faqs/create')->assertOk()->assertSee('Leave blank to add at the end');
    }

    public function test_admin_can_add_a_faq(): void
    {
        $this->post('/admin/faqs', $this->validPayload(['is_featured' => '1']))
            ->assertRedirect('/admin/faqs')
            ->assertSessionHas('status');

        $faq = Faq::sole();
        $this->assertSame('Do you pull permits?', $faq->question);
        $this->assertSame(FaqCategory::Process, $faq->category);
        $this->assertSame(3, $faq->sort_order);
        $this->assertTrue($faq->is_featured);
    }

    public function test_blank_sort_order_appends_after_the_current_maximum(): void
    {
        Faq::factory()->create(['sort_order' => 7]);
        Faq::factory()->create(['sort_order' => 2]);

        $this->post('/admin/faqs', $this->validPayload(['sort_order' => '']))->assertRedirect('/admin/faqs');

        $this->assertSame(8, Faq::latest('id')->first()->sort_order);
    }

    public function test_admin_can_edit_a_faq(): void
    {
        $faq = Faq::factory()->create(['question' => 'Old question?', 'sort_order' => 4]);

        $this->get("/admin/faqs/{$faq->id}/edit")->assertOk()->assertSee('Old question?');

        $this->put("/admin/faqs/{$faq->id}", $this->validPayload([
            'question' => 'New question?',
            'category' => 'pricing',
            'sort_order' => '9',
        ]))->assertRedirect('/admin/faqs');

        $faq->refresh();
        $this->assertSame('New question?', $faq->question);
        $this->assertSame(FaqCategory::Pricing, $faq->category);
        $this->assertSame(9, $faq->sort_order);
    }

    public function test_blank_sort_order_on_edit_keeps_the_current_position(): void
    {
        $faq = Faq::factory()->create(['sort_order' => 4]);

        $this->put("/admin/faqs/{$faq->id}", $this->validPayload(['sort_order' => '']))->assertRedirect('/admin/faqs');

        $this->assertSame(4, $faq->refresh()->sort_order);
    }

    public function test_admin_can_delete_a_faq(): void
    {
        $faq = Faq::factory()->create();

        $this->delete("/admin/faqs/{$faq->id}")->assertRedirect('/admin/faqs');

        $this->assertModelMissing($faq);
    }

    public function test_required_fields_are_enforced(): void
    {
        $this->post('/admin/faqs', [])->assertSessionHasErrors(['question', 'answer', 'category']);
    }

    public function test_unknown_category_is_rejected(): void
    {
        $this->post('/admin/faqs', $this->validPayload(['category' => 'gossip']))->assertSessionHasErrors('category');

        $this->assertDatabaseCount('faqs', 0);
    }

    public function test_negative_sort_order_is_rejected(): void
    {
        $this->post('/admin/faqs', $this->validPayload(['sort_order' => '-1']))->assertSessionHasErrors('sort_order');
    }
}

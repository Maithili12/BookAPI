<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    public function test_can_list_books()
    {
        Book::factory()->count(5)->create();
        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
        ->assertJson(['status' => 'success'])
        ->assertJsonStructure([
            'status',
            'data' => [
                'current_page',
                'data' => [
                    ['id', 'title', 'author', 'published_date', 'genre']
                ],
            ],
        ]);
    }


    public function test_can_create_book()
    {
        $response = $this->postJson('/api/books', [
            'title' => 'Test Book',
            'author' => 'Test Author',
            'published_date' => '2023-10-01',
            'genre' => 'Fiction',
        ]);

        $response->assertStatus(201)
            ->assertJson(['status' => 'success'])
            ->assertJsonFragment(['title' => 'Test Book']);
    }

    public function test_can_show_book()
    {
        $book = Book::factory()->create();
        $response = $this->getJson('/api/books/' . $book->id);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success', 'data' => ['id' => $book->id]]);
    }

    public function test_can_update_book()
    {
        $book = Book::factory()->create();
        $response = $this->putJson('/api/books/' . $book->id, [
            'title' => 'Updated Book',
            'author' => 'Updated Author',
            'published_date' => '2023-10-01',
            'genre' => 'Non-Fiction',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Book updated successfully',
                'data' => [
                    'title' => 'Updated Book',
                    'author' => 'Updated Author',
                    'published_date' => '2023-10-01',
                    'genre' => 'Non-Fiction',
                    'id' => $book->id,
                ],
            ]);
    }

    public function test_can_delete_book()
    {
        $book = Book::factory()->create();
        $response = $this->deleteJson('/api/books/' . $book->id);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success', 'message' => 'Book deleted successfully']);
    }

    public function test_search_books()
    {
        $book = Book::factory()->create(['author' => 'Test Author']);
        $response = $this->getJson('/api/books/search?search=Test Author');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => $book->title]);
    }
}

<?php

use App\Models\User;

test('returns paginated users in JSON:API format', function () {
    User::factory()->count(25)->create();

    $response = $this->getJson('/api/users');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['type', 'id', 'attributes' => ['name', 'created_at']],
            ],
            'links' => ['self', 'first', 'last', 'prev', 'next'],
            'meta' => ['pagination' => ['total', 'per_page', 'current_page', 'last_page']],
        ]);

    expect(count($response->json('data')))->toBe(10);
});

test('supports sorting by name and email', function () {
    User::factory()->create(['name' => 'Alice', 'email' => 'b@example.com']);
    User::factory()->create(['name' => 'Bob', 'email' => 'a@example.com']);

    $response = $this->getJson('/api/users?sort=name');
    $response->assertStatus(200);
    $names = array_map(fn ($item) => $item['attributes']['name'], $response->json('data'));
    expect($names[0])->toBe('Alice');

    $response2 = $this->getJson('/api/users?sort=-email');
    $response2->assertStatus(200);
    $ids = array_map(fn ($i) => (int) $i['id'], $response2->json('data'));
    $firstUser = User::find($ids[0]);
    expect($firstUser->email)->toBe('b@example.com');
});

test('pagination returns correct page', function () {
    User::factory()->count(15)->create();

    $response = $this->getJson('/api/users?page=2');
    $response->assertStatus(200);

    expect(count($response->json('data')))->toBe(5);
    expect($response->json('meta.pagination.current_page'))->toBe(2);
});

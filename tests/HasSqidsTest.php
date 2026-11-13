<?php

use Guava\Sqids\Sqids;
use Guava\Sqids\Tests\Models\Comment;
use Guava\Sqids\Tests\Models\Post;
use Illuminate\Database\Eloquent\Relations\Relation;

afterEach(fn () => Relation::morphMap([], merge: false));

it('salts a model\'s sqid with its class when no salt is given', function () {
    $post = Post::create();

    expect($post->sqid)->toBe(Sqids::make()->salt(Post::class)->encode($post->id));
});

it('gives two models with the same id different sqids', function () {
    $post = Post::create();
    $comment = Comment::create();

    expect($post->id)->toBe($comment->id)
        ->and($post->sqid)->not->toBe($comment->sqid)
    ;
});

it('salts with the morph alias, which survives renaming the model', function () {
    Relation::morphMap(['post' => Post::class]);
    $post = Post::create();

    expect($post->sqid)->toBe(Sqids::make()->salt('post')->encode($post->id));
});

it('finds a model by its salted sqid in a route binding', function () {
    $post = Post::create();

    expect((new Post)->resolveRouteBinding($post->sqid)?->is($post))->toBeTrue();
});

<?php

test('may welcome the user', function () {
    $page = visit('/');
 
    $page->assertSee('Welcome');
});
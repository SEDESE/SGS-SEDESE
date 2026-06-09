<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
 * Aplica o TestCase do Laravel e RefreshDatabase a todos os testes em tests/Feature.
 * Cada teste roda num banco SQLite em memória isolado (ver phpunit.xml).
 */
uses(TestCase::class, RefreshDatabase::class)->in('Feature');

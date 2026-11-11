<?php

namespace Tests\Feature;

use Tests\TestCase;

class FilamentPanelTest extends TestCase
{
    public function test_admin_panel_login_is_available_without_registration(): void
    {
        $this->get('/admin/login')->assertOk();
        $this->get('/admin/register')->assertNotFound();
    }
}

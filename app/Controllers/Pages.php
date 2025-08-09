<?php
// ============================================================================
// ДОПОЛНИТЕЛЬНЫЕ ФАЙЛЫ
// ============================================================================

// app/Controllers/Pages.php - для статических страниц
class Pages extends BaseController
{
    public function privacy()
    {
        $data = [
            'title' => 'Privacy Policy - Barcelona Wineries',
            'meta_description' => 'Privacy Policy for Barcelona Wineries website'
        ];
        
        return view('pages/privacy', $data);
    }
}

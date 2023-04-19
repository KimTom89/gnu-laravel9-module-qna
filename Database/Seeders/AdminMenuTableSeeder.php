<?php

namespace Modules\Qna\Database\Seeders;

use App\Models\AdminMenu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class AdminMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $qnaAdminMenu = [
            ['label' => 'Q&A', 'menu' => '', 'link' => '', 'children' => [
                ['label' => 'Q&A 목록', 'menu' => 'admin.qna', 'link' => 'admin.qna.index'],
                ['label' => '설정관리', 'menu' => 'admin.qna-config', 'link' => 'admin.qna-config.index'],
                ['label' => '카테고리 관리', 'menu' => 'admin.qna-category', 'link' => 'admin.qna-category.index'],
            ]],
        ];

        $maxPosition = AdminMenu::whereNull('parent_id')->max('position');

        foreach ($qnaAdminMenu as $parent) {
            $parentId = AdminMenu::create([
                'label'    => $parent['label'],
                'menu'     => $parent['menu'],
                'link'     => $parent['link'],
                'position' => $maxPosition + 1,
            ])->id;

            foreach ($parent['children'] as $cIdx => $child) {
                AdminMenu::create([
                    'parent_id' => $parentId,
                    'label'     => $child['label'],
                    'menu'      => $child['menu'],
                    'link'      => $child['link'],
                    'position'  => $cIdx + 1,
                ]);
            }
        }
    }
}

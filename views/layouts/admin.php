<?php
/** @var yii\web\View $this */
/** @var string $content */
// Extend the main layout
$this->beginContent('@app/views/layouts/main.php');
?>
<div class="flex min-h-screen bg-gray-50">
    <aside class="w-64 bg-white border-r border-gray-200 shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800"><?= Yii::t('app/admin', 'Administration') ?></h2>
        </div>
        
        <nav class="p-4">
            <div class="space-y-1">
                <a href="<?= \yii\helpers\Url::to(['/admin/user/index']) ?>" 
                   class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                          <?= Yii::$app->controller->id === 'user' ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' ?>">
                    <i class="fas fa-users w-5 h-5 mr-3 transition-colors"></i>
                    <span><?= Yii::t('app/admin', 'Users') ?></span>
                </a>

                <a href="<?= \yii\helpers\Url::to(['/admin/post/index']) ?>" 
                   class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                          <?= Yii::$app->controller->id === 'post' ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' ?>">
                    <i class="fas fa-images w-5 h-5 mr-3 transition-colors"></i>
                    <span><?= Yii::t('app/admin', 'Posts') ?></span>
                </a>

                <a href="<?= \yii\helpers\Url::to(['/admin/group/index']) ?>" 
                   class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                          <?= Yii::$app->controller->id === 'group' ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' ?>">
                    <i class="fas fa-user-friends w-5 h-5 mr-3 transition-colors"></i>
                    <span><?= Yii::t('app/admin', 'Groups') ?></span>
                </a>

                <a href="<?= \yii\helpers\Url::to(['/admin/comment/index']) ?>" 
                   class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                          <?= Yii::$app->controller->id === 'comment' ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' ?>">
                    <i class="fas fa-comment w-5 h-5 mr-3 transition-colors"></i>
                    <span><?= Yii::t('app/admin', 'Comments') ?></span>
                </a>

                <a href="<?= \yii\helpers\Url::to(['/admin/tag/index']) ?>" 
                   class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
                          <?= Yii::$app->controller->id === 'tag' ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-orange-50 hover:text-orange-600' ?>">
                    <i class="fas fa-tag w-5 h-5 mr-3 transition-colors"></i>
                    <span><?= Yii::t('app/admin', 'Tags') ?></span>
                </a>
            </div>

            <!-- Optional: Add a footer section to the sidebar -->
            <div class="mt-8 pt-4 border-t border-gray-200">
                <div class="px-4 py-2">
                    <p class="text-xs text-gray-500">
                        <?= Yii::t('app/admin', 'Logged in as {username}', ['username' => Yii::$app->user->identity->username ?? 'Admin']) ?>
                    </p>
                </div>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 overflow-x-hidden bg-gray-50">
        <div class="p-8">
            <?= $content ?>
        </div>
    </div>
</div>
<?php $this->endContent(); ?>

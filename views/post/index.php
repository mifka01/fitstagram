<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $postDataProvider */

use app\components\ScrollPager;
use yii\helpers\Url;
use yii\widgets\ListView;

$this->title = Yii::t('app', 'Posts');
$loginUrl = Url::to(['auth/login']);

$js = <<<JS
document.getElementById("list-view-posts").addEventListener('click',  async (event) => {
    let target = $(event.target);

    if(target.hasClass('page-link') && target.data('page') !== undefined){
        event.preventDefault();
        let href = event.target.href;
        let comments = $(event.target.closest('.list-view'))

        $.ajax({
            url: href,
            type: 'GET',
            success: function(response) {
                comments.html(response);
            },
            error: function() {
                console.log('Error loading page');
            }
        });
        return;
    }

    if (target.hasClass('vote-button') && event.target.type == "submit") {
        event.preventDefault();
        const form = $(event.target.closest("form"));
        const postId = form.find('#postvoteform-postid').val();
        const type= target.val();
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: '/post/vote',
            type: 'POST',
            dataType: 'json',
            data: {
                PostVoteForm: {
                    postId: postId,
                    type: type,
                },
                _csrf: csrfToken
            },
            success: function(response) {
                if (response.success) {
                    form.replaceWith(response.html);
                } else {
                    let errorMessage = 'Error submitting vote:\\n';
                    $.each(response.errors, function(field, messages) {
                        errorMessage += messages.join('\\n');
                    });
                    console.log(errorMessage);
                }
            },
            error: function() {
                window.location.href = "$loginUrl";
            }
        });
        return;
    }

  if (target.hasClass('comment-submit') && event.target.type == "submit") {
        event.preventDefault();
        const form = $(event.target.closest("form"));
        const contentField = form.find('#commentform-content');

        if(contentField.val().length === 0) {
            return;
        }

        const postId = form.find('#commentform-postid').val();
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const commentsContainer = form.closest('.comments');
        $.ajax({
            url: '/comment/create',
            type: 'POST',
            dataType: 'json',
            data: {
                CommentForm: {
                    content: contentField.val(),
                    postId: postId,
                },
                _csrf: csrfToken
            },
            success: function(response) {
                if (response.success) {
                    commentsContainer.html(response.html);
                } else {
                    let errorMessage = 'Error submitting comment:\\n';
                    $.each(response.errors, function(field, messages) {
                        errorMessage += messages.join('\\n');
                    });
                    console.log(errorMessage);
                }
            },
            error: function() {
                window.location.href = "$loginUrl";
            }
        });
        return;
  }
});
JS;

Yii::$app->view->registerJs($js, \yii\web\View::POS_END);
?>

<div id="carousel-wrapper" class="bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8 space-y-6">
    <?= ListView::widget([
        'id' => 'list-view-posts',
        'dataProvider' => $postDataProvider,
        'itemView' => '_post',
        'itemOptions' => ['class' => 'item mb-4'],
        'layout' => "{items}\n{pager}",
        'summary' => '',
        'pager' => [
            'class' => ScrollPager::class,
            'pagination' => $postDataProvider->getPagination(),
            'scrollOffset' => 1000,
            'id' => 'post-scroll-pager',
            'clientOptions' => [
                'throttleWait' => 50,
            ],
            'label' => Yii::t('app', 'Load More'),
        ],
    ]); ?>

</div>

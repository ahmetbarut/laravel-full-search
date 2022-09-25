<?php

use AhmetBarut\FullSearch\Tests\Models\Post;

return [

    /**
     * Üzerinde arama yapılacak modelleri tanımlayın.
     * models altında arama yapılabilir olarak açık olacaktır.
     */

    'models' => [
        Post::class => [

            // Eğer aramalara kapatmak isterseniz false yapın.
            // Bu durumda bu model üzerinden arama yapmayı kapatacaktır.
            'searchable' => true,

            /**
             * Model üzerinde arama yapanları kısıtlamak isterseniz bu kısmı true yapın.
             * Bu şekilde sadece yetkili kullanıcılar için açık olacaktır.
             * `ability` özelliği, laravel'in `Gate` özelliğinden faydalanır.
             * @link https://laravel.com/docs/9.x/authorization#gates
             */
            'ability' => false,

            /**
             * Arama yapılacak alanları belirtin. Yani bu alanlar belirtildiğinde,
             * alanlar üzerinden aramaları yapacak ve sonuç bulunursa getirecektir.
             */
            'searchable_fields' => [
                'title',
                'summary',
                'content',
                'slug',
            ],
            // Toplam sonuç sayısını sınırlamak için kullanılır.
            'max_results' => 80,

            // Arama sonuçlarının yönlendirileceği route bilgileri.
            'route' => [

                // Route adı
                'name' => 'post',

                // Yönlendirmenin yapılacağı route parametreleri.
                // Örneğin route adı `user` ise, `user/{user}` şeklinde bir route tanımlanmışsa
                // `slug_parameter_name` => 'user' şeklinde tanımlanmalıdır.
                'slug_parameter_name' => 'post',

                // full-search paketi çalışırken, `slug` değerine bakar bunun nedenei ise
                // url'de `slug` değeri ile yönlendirme yapılmasıdır.
                // yani siz `id` olarak tanımlarsanız, /user/1 şeklinde bir url oluşturur.
                'parameters' => [
                    'slug' => 'id',
                ],

                // Arama sonuçlarının yönlendirileceği route parametreleri.
                // Burada yine slug üzerinden gidilir fakat route'leri eşleştirirken
                // `slug_parameter_name` değerlerine bakar.
                // Şu an da sadece `slug` değeri ile yönlendirme yapılabilir.
                // Bunun kullanıldığı yer ise
                // Route::get('/users/{user}', [UserController::class, 'show'])->name('user');
                // `user` parametresini değiştirerek kullanır.
                'replace_parameters' => [
                    'slug' => 'post',
                ],
            ],

            // Arama sonuçlarında dönecek parametreler.
            // şu an için sadece `title` ve `url` parametreleri kullanılabilir.
            // `page` değeri ise hangi sayfa için dönüş yapıldığını belirtir.
            'url_parameter_name' => 'slug',
            'response_parameters' => [
                'title' => 'title',
                'url' => 'page/{slug}',
                'page' => 'page'
            ],
        ],
    ],

    /**
     * Aramaların gerçekleşeceği istek url'si. Burası varsayılan `/full-search/results` olarak ayarlanmıştır.
     * Bunu değiştirmek isterseniz buradan değiştirebilirsiniz. Örneğin `arama` olarak değiştirmek isterseniz
     * `route` => 'arama' olarak ayarlayabilirsiniz.
     */
    'route' => '/full-search/results',
];

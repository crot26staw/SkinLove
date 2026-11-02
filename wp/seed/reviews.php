<?php
/**
 * Сид отзывов — общие на весь сайт, тексты из вёрстки главной.
 *
 * Запуск: docker compose run --rm wpcli wp eval-file /seed/reviews.php
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/_lib.php';

$reviews = array(
	array(
		'name'  => array( 'Юлия', 'Yulia' ),
		'text'  => array(
			'Лучшая клиника в Дубае! Атмосфера тёплая и уютная, персонал работает на высшем уровне. Особенно благодарна Елене — настоящий мастер своего дела. Результат превзошёл все ожидания. Рекомендую всем!',
			'The best clinic in Dubai! The atmosphere is warm and welcoming, and the team is truly outstanding. Special thanks to Elena — a real master of her craft. The result exceeded all my expectations. I recommend it to everyone!',
		),
		'photo' => 'review-1.jpg',
	),
	array(
		'name'  => array( 'Валерия', 'Valeria' ),
		'text'  => array(
			'Очень внимательный и профессиональный сервис. Чувствуется, что здесь действительно заботятся о каждом клиенте. Сотрудники компетентные, всё объясняют подробно. Осталась в полном восторге!',
			'Very attentive, professional service. You can feel that they genuinely care about every client here. The staff are knowledgeable and explain everything in detail. I left absolutely delighted!',
		),
		'photo' => 'review-2.jpg',
	),
	array(
		'name' => array( 'Мария', 'Maria' ),
		'text' => array(
			'Пришла в Skin Love Medical Clinic впервые и сразу поняла — это место, куда хочется возвращаться. Тёплая атмосфера, отличный сервис и видимый результат уже после первой процедуры. Спасибо команде!',
			'My first visit to Skin Love Medical Clinic, and I knew right away this is a place I’ll keep coming back to. A warm atmosphere, excellent service and a visible result after the very first treatment. Thank you to the team!',
		),
	),
	array(
		'name'  => array( 'Сэм', 'Sam' ),
		'text'  => array(
			'Елена — настоящий профессионал и прекрасный собеседник. Делает всё аккуратно, с душой. Клиника чистая, современная, отношение к клиентам на высшем уровне. Очень доволен!',
			'Elena is a true professional and wonderful to talk to. Everything is done carefully and with heart. The clinic is clean and modern, and the way they treat clients is first-class. Very happy!',
		),
		'photo' => 'review-4.jpg',
	),
	array(
		'name'  => array( 'Анастасия', 'Anastasia' ),
		'text'  => array(
			'Это лучший сервис, который я встречала в Дубае. Всё на высшем уровне: от встречи до результата. Персонал внимательный, знающий, атмосфера очень комфортная. Рекомендую от всей души!',
			'The best service I’ve come across in Dubai. Everything is first-class, from the welcome to the result. The staff are attentive and knowledgeable, and the atmosphere is very comfortable. I recommend it wholeheartedly!',
		),
		'photo' => 'review-5.jpg',
	),
	array(
		'name' => array( 'Ольга', 'Olga' ),
		'text' => array(
			'Профессионалы своего дела! Чувствуется высокий уровень и забота. Особенно понравилась тёплая, почти домашняя атмосфера. Результат процедур отличный. Буду ходить только сюда.',
			'True professionals! You can feel the high standard and the care. I especially loved the warm, almost homely atmosphere. The results of the treatments are excellent. This is the only place I’ll go from now on.',
		),
	),
	array(
		'name' => array( 'Екатерина', 'Ekaterina' ),
		'text' => array(
			'Очень довольна обслуживанием и качеством работы. Сотрудники внимательные, всё объясняют понятно, без лишней спешки. Елена — настоящий мастер. Клиника однозначно стоит внимания.',
			'Very pleased with the service and the quality of the work. The staff are attentive and explain everything clearly, without rushing. Elena is a true master. The clinic is definitely worth a visit.',
		),
	),
	array(
		'name'  => array( 'Дарья', 'Daria' ),
		'text'  => array(
			'Приятно удивлена уровнем сервиса. Здесь не просто делают процедуры, а действительно заботятся о результате и комфорте клиента. Атмосфера тёплая, персонал профессиональный. Спасибо Skin Love!',
			'Pleasantly surprised by the level of service. They don’t just perform treatments here — they genuinely care about the result and the client’s comfort. A warm atmosphere and a professional team. Thank you, Skin Love!',
		),
		'photo' => 'review-8.jpg',
	),
	array(
		'name' => array( 'Ирина', 'Irina' ),
		'text' => array(
			'Отличная клиника! Чисто, современно, персонал очень внимательный и компетентный. Особенно хочу отметить Елену — работает аккуратно и с большим профессионализмом. Результат радует.',
			'An excellent clinic! Clean, modern, with a very attentive and competent team. I’d especially like to mention Elena — she works carefully and with great professionalism. I’m delighted with the result.',
		),
	),
	array(
		'name' => array( 'Светлана', 'Svetlana' ),
		'text' => array(
			'Лучшее, что я находила в Дубае по уходу за кожей. Тёплая атмосфера, высокий уровень сервиса и реальные результаты. Команда Skin Love Medical Clinic — настоящие профессионалы. Рекомендую!',
			'The best skincare I’ve found in Dubai. A warm atmosphere, a high level of service and real results. The Skin Love Medical Clinic team are true professionals. Highly recommended!',
		),
	),
);

foreach ( $reviews as $order => $review ) {
	$id = skinlove_seed_post( 'sl_review', $review['name'][0], array( 'menu_order' => $order + 1 ) );

	skinlove_seed_pair( $id, 'name', ...$review['name'] );
	skinlove_seed_pair( $id, 'text', ...$review['text'] );
	carbon_set_post_meta( $id, 'sl_photo', isset( $review['photo'] ) ? skinlove_seed_image( $review['photo'] ) : 0 );
}

WP_CLI::success( 'Отзывов: ' . count( $reviews ) );

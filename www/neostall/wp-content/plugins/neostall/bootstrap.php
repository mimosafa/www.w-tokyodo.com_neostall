<?php
namespace Neostall;
use mimosafa\WP;
class Bootstrap {
	private $post_types = [
		'activity'   => 'label=アクティビティ&show_ui=1',
		'management' => 'label=管理情報&=1&public=1&has_archive=1',
		'event'      => 'label=イベント&public=1&has_archive=1',
		'vendor'     => 'label=事業者&show_ui=1',
		'kitchencar' => 'label=キッチンカー&public=1&has_archive=1',
		'menu_item'  => 'label=提供商品&show_ui=1',
		'space'      => 'label=ランチスペース&public=1&has_archive=1',
		'news'       => 'label=ニュース&public=1&has_archive=1',
	];
	private $taxonomies = [
		'series' => 'label=イベントシリーズ&public=1&object_type=event&hierarchical=1',
		'region' => 'label=地域&public=1&object_type=event+space&hierarchical=1',
		'genre'  => 'label=ジャンル&public=1&object_type=vendor+menu_item'
	];
	public static function init() {
		static $ins;
		$ins ?: $ins = new self();
	}
	private function __construct() {
		$this->init_components();
	}
	private function init_components() {
		foreach ( $this->post_types as $name => $args ) {
			WP\Repository\PostType::generate( $name, '', $args );
		}
		foreach ( $this->taxonomies as $name => $args ) {
			WP\Repository\Taxonomy::generate( $name, '', $args );
		}
	}
}

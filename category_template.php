<li>
	<a class="get_url"  id="get_url" href="<?=PATH?>?category=<?=$category['id']?>"><?=$category['title']?></a>
	<?php if($category['childs']): ?>
	<ul class="submenuItems">
		<?php echo $this->categories_to_string($category['childs']); ?>
	</ul>
	<?php endif; ?>
</li>
<div class="text-center">
	<ul class="pagination">
		<?php if ($pagination->current_page === 1): ?>
			<li class="disabled"><span>&laquo;</span></li>
		<?php else: ?>
			<li><a href="<?php echo $url.($pagination->current_page - 1); ?>">&laquo;</a></li>
		<?php endif ?>
		<?php for($i = max($pagination->current_page - 2, 1); $i <= min($pagination->current_page + 2, $pagination->total_pages); $i++): ?>
			<?php if ($i === $pagination->current_page): ?>
				<li class="active"><span><?php echo $i; ?> <span class="sr-only">(current)</span></span></li>
			<?php else: ?>
				<li><a href="<?php echo $url.$i; ?>"><?php echo $i; ?></a></li>
			<?php endif ?>
		<?php endfor; ?>
		<?php if ($pagination->current_page == $pagination->total_pages): ?>
			<li class="disabled"><span>&raquo;</span></li>
		<?php else: ?>
			<li><a href="<?php echo $url.($pagination->current_page + 1); ?>">&raquo;</a></li>
		<?php endif ?>
	</ul>
</div>
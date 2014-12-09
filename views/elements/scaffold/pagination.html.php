<?php
$model = $this->scaffold->model;

$prev = $page-1;
$next = $page+1;

$pageUri = preg_replace('/((\&|\?)p=\d+)/', '', $_SERVER['REQUEST_URI']);
$pageUri .= (strpos($pageUri, '?') !== false) ? '&' : '?';
$pageUri .= 'p=';

if($offsets['offset'] <= 0) return false;

?>

<div class="pageination">
	<div class="pull-right">
		<div class="dataTables_paginate paging_bs_normal">
			<ul class="pagination">
				<li class="prev <? if($prev <=0) echo "disabled"?>">
					<a href="<?=$pageUri.'1';?>">
						<span class="fa fa-angle-left"></span>
						<span class="fa fa-angle-left"></span>
					</a>
				</li>
				<li class="prev <? if($prev <=0) echo "disabled"?>">
					<a href="<?=$pageUri.$prev;?>">
						<span class="fa fa-angle-left"></span>
						Previous
					</a>
				</li>
				<?php
					for($i=$page-5;$i<$page+5;$i++){
						if($i<1) continue;
						if($i>$offsets['pages']) continue;
						?>
						<li class="<? if($i == $page) echo "active"?>">
							<a href="<?=$pageUri.$i;?>"><?=$i;?></a>
						</li>
						<?php
					}
				?>
				<li class="next <? if($next > $offsets['pages']) echo "disabled"?>">
					<a href="<?=$pageUri.$next;?>">
						Next
						<span class="fa fa-angle-right"></span>
					</a>
				</li>
				<li class="next <? if($next > $offsets['pages']) echo "disabled"?>">
					<a href="<?=$pageUri.$offsets['pages'];?>">
						<span class="fa fa-angle-right"></span>
						<span class="fa fa-angle-right"></span>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>

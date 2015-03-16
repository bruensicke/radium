<?php
$model = $this->scaffold->model;

if(!isset($offsets)) return false;
if($offsets['pages'] <= 1) return false;

$prev = $page-1;
$next = $page+1;

$pageUri = preg_replace('/((\&|\?)p=\d+)/', '', $_SERVER['REQUEST_URI']);
$pageUri .= (strpos($pageUri, '?') !== false) ? '&' : '?';
if(isset($conditions) && !isset($_GET['q'])){
	$pageUri .= 'q='.base64_encode(json_encode($conditions)).'&';
}
if(isset($collection) && !isset($_GET['c'])){
	$pageUri .= 'collection='.$collection.'&';
}
$pageUri .= 'p=';
$start = ($offsets['offset'] <= 0) ? 1 : $offsets['offset'];
?>

<div class="pageination">
	<div class="pull-left">
		<div>Showing <?=$start;?> to <?=$offsets['limit'];?> of <?=$count;?> entries</div>
	</div>
	<div class="pull-right">
		<div>
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
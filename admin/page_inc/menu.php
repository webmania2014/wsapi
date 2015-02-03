<ul id = 'menuUl'>

<?php
	$bFirst = true;
	foreach ( $arrMenu as $menu => $menuItem )
	{
		$title = $menuItem['title'];
		$show  = $menuItem['show'];

		$class = 'link1';
		if ( $getMenu == '' )
		{
			if ( $bFirst )
			{
				$class = 'link2';
				$bFirst = false;
			}
		}

		if ( $getMenu != '' && $getMenu == $menu )
			$class = 'link2';

		if ( $show )
		{
			echo '
				<li>
					<a class = "' . $class . '" href = "index.php?menu=' . $menu . '">' . $title . '</a>
				</li>
			';
		}
	}
?>
</ul>
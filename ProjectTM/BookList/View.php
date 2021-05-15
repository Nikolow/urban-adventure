<?php
namespace BookList;

class View 
{
	public function show(\Test\BookList $Model): string 
	{
		$output = 
		'
		<link rel="stylesheet" href="css/mainStyle.css">
		<section>
			<h1>Books</h1>
		';

		$output .= 
		'
			<form class="cntr" action="" method="get">
				<input type="hidden" value="filterBooks" name="route" />
				<input type="hidden" value="' . $Model->getSort() . '" name="sort" />
				<input type="text" class="myInput" placeholder="Enter Author name.." name="search" />

				<input type="submit" value="Search" class="myBtn" />

				<p style="color: #fff;">Sort: <a href="index.php?route=filterBooks&amp;sort=newest">Newest first</a> | <a href="index.php?route=filterBooks&amp;sort=oldest">Oldest first</a></p>
			</form>
			
		';

		$output .= '
			<div class="tbl-header">
				<table cellpadding="0" cellspacing="0" border="0">
					<thead>
						<tr class="heads">
							<th>Author</th>
							<th>Book</th>
							<th>Action</th>
						</tr>
					</thead>
				</table>
			</div>';


		$output .= '
			<div class="tbl-content">
				<table cellpadding="0" cellspacing="0" border="0">
					<tbody>';

		
					foreach ($Model->getBooks() as $book) 
					{
						$output .= 	'<tr class="active">';

							$output .= '<td>' . $book['book_name'] . '</td>';
							$output .= '<td>' . $book['author_name'].'</td>';
							$output .= '<td> <form action="index.php?route=delete" method="POST"> <input type="hidden" name="book_id" value="' . $book['book_id'] . '" /> <input type="submit" class="myBtn" value="Delete" /> </form> </td>';

						$output .= 	'</tr>';
					}
		$output .= '
					</tbody>
				</table>
			</div>
		</section>';



		$output .= 
		'
			<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

			<script>

				$(window).on("load resize", function() 
				{
					let scrollWidth = $(".tbl-content").width() - $(".tbl-content table").width();
					$(".tbl-header").css({"padding-right":scrollWidth});
				}).resize();


				$(".active").each(function(index)
				{
					$(this).css("animation-delay",index *0.5 +"s");
					$(this).show(index*500, function()
					{
						$(this).css("display", "table-row");
						$(this).css("opacity", "1");
					});
				});

			</script>
		';



		return $output;

	}
}

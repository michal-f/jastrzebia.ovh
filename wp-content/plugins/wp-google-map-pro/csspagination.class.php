<?php

class Wpgmp_Pagination

{

	private $totalrows;

	public $rowsperpage;

	public $website;

	private $page;

	public $sql;

	private $mod;

	private $do;

	private $showpage=1;	

    public $map_id;
	public function __construct($sql='', $rowsperpage=0, $page='',$map_id)

	{

		$this->sql = $sql;

		$this->page = $page;

		$this->rowsperpage = $rowsperpage;

	    $this->map_id=$map_id;
	}

	

	public function setPage($page)

	{

		if (!$page) { $this->page=1; } else  { $this->page = $page; }

	}

	

	public function getLimit()

	{

		return ($this->page - 1) * $this->rowsperpage;

	}

	

	private function getTotalRows()

	{

		global $wpdb;

		$result = $wpdb->get_results($this->sql);	

		$this->totalrows = count($result);

	}

	

	private function getLastPage()

	{

		return ceil($this->totalrows / $this->rowsperpage);

	}

	

	public function showPage()

	{

		$this->getTotalRows();

		$pagination = "";

		$lpm1 = $this->getLastPage() - 1;

		$page = $this->page;

		$prev = $this->page - 1;

		$next = $this->page + 1;

		

		$pagination .= "<div class=\"pagination\"";

		if($margin || $padding)

		{

			$pagination .= " style=\"";

			if($margin)

				$pagination .= "margin: $margin;";

			if($padding)

				$pagination .= "padding: $padding;";

			$pagination .= "\"";

		}

		$pagination .= ">";

		

		

		

		if ($this->getLastPage() > 1)

		{

			if ($page > 1) 

				$pagination .= "<a href=javascript:wpgmp_filter_locations($this->map_id,$prev)> Previous</a>";

			else

				$pagination .= "<span class=\"disabled\">Previous</span>";

			

			

			if ($this->getLastPage() < 9)

			{	

				for ($counter = 1; $counter <= $this->getLastPage(); $counter++)

				{

					if ($counter == $page)

						$pagination .= "<span class=\"current\">".$counter."</span>";

					else

						$pagination .= "<a href=javascript:wpgmp_filter_locations($this->map_id,$counter)>".$counter."</a>";					

				}

			}

			

			elseif($this->getLastPage() >= 9)

			{

				if($page < 4)		

				{

					for ($counter = 1; $counter < 6; $counter++)

					{

						if ($counter == $page)

							$pagination .= "<span class=\"current\">".$counter."</span>";

						else

							$pagination .= "<a href=javascript:wpgmp_filter_locations($this->map_id,$counter) />".$counter."</a>";					

					}

					$pagination .= "...";

					$pagination .= "<a href=javascript:wpgmp_filter_locations($this->map_id,".$lpm1.")>".$lpm1."</a>";

					$pagination .= "<a href=javascript:wpgmp_filter_locations($this->map_id,".$this->getLastPage().")>".$this->getLastPage()."</a>";		

				}

				elseif($this->getLastPage() - 3 > $page && $page > 1)

				{

					$pagination .= "<a href=javascript:wpgmp_filter_locations($this->map_id,1)>1</a>";

					$pagination .= "<a href=javascript:wpgmp_filter_locations($this->map_id,2)>2</a>";

					$pagination .= "...";

					for ($counter = $page - 1; $counter <= $page + 1; $counter++)

					{

						if ($counter == $page)

							$pagination .= "<span class=\"current\">".$counter."</span>";

						else

							$pagination .= "<a href=javascript:wpgmp_filter_locations($this->map_id,$counter)>".$counter."</a>";					

					}

					$pagination .= "...";

					$pagination .= "<a href=$this->website&page=$lpm1>$lpm1</a>";

					$pagination .= "<a href=javascript:wpgmp_filter_locations(".$this->map_id.",".$this->getLastPage().")>".$this->getLastPage()."</a>";		

				}

				else

				{

					$pagination .= "<a href=javascript:wpgmp_filter_locations($this->map_id,1)>1</a>";

					$pagination .= "<a href=javascript:wpgmp_filter_locations($this->map_id,2)>2</a>";

					$pagination .= "...";

					for ($counter = $this->getLastPage() - 4; $counter <= $this->getLastPage(); $counter++)

					{

						if ($counter == $page)

							$pagination .= "<span class=\"current\">".$counter."</span>";

						else

							$pagination .= "<a href=javascript:wpgmp_filter_locations($this->map_id,$counter)>".$counter."</a>";					

					}

				}

			}

		

		if ($page < $counter - 1) 

			$pagination .= "<a href=javascript:wpgmp_filter_locations($this->map_id,$next)>Next</a>";

		else

			$pagination .= "<span class=\"disabled\">Next</span>";

		$pagination .= "</div>\n";			

		}	

		return $pagination;

	}

	public function GetResult()

	{

		global $wpdb;

		 // other arguments if need it.

		 if($this->page)

		$this->setPage($this->page); // dont change it

		else

		$this->setPage($this->showPage); // dont change it

		$query=$this->sql." LIMIT " . $this->getLimit() . ", " . $this->rowsperpage;

		return $wpdb->get_results($query);

	}

}

?>

<?php
//Functions for loading, connecting and displaying the tree are in this file

class treenode {
	//each node in the tree has memeber variables containing all the data for a post except the body of message
	
	public $m_postid;
	public $m_poster;
	public $m_posted;
	public $m_children;
	public $m_childlist;
	public $m_depth;
	public $m_expand;
	public $m_expanded;
	public $m_sublist;
	public $m_root;
	public $m_scopeid;
	
	public function __construct($postid, $poster, $posted, $children, $expand, $depth, $expanded, $sublist, $root, $scopeid) {
		//The constructor sets up the member variables, but more importantly recursively creates lower parts of the tree
		$this->m_postid = $postid;
		$this->m_poster = $poster;
		$this->m_posted = $posted;
		$this->m_children = $children;
		$this->m_childlist = array();
		$this->m_depth = $depth;
		$this->m_expand = $expand;
		$this->m_expanded = $expanded;
		$this->m_sublist = $sublist;
		$this->m_root = $root;
		$this->m_scopeid = $scopeid;	
	}
	
	function fetchingAll() {
		//Used for fetching all the messages from the same scope
		//Only if it is a root node
		if ($this->m_root) {
			
			$conn = db_connect();
			
			$query = "select * from MSG_DTLS where scopeId = '".$this->m_scopeid."' order by posted LIMIT 30";
			
			$result = $conn->query($query);
			
			for($count=0; $row = @$result->fetch_assoc(); $count++) {
				$this->m_childlist[$count] = new treenode ($row['postId'],
				$row['poster'], $row['postId'], $row['children'], $this->m_expand, $this->m_depth+1, $this->m_expanded, $this->m_sublist, false, $this->m_scopeid);
			}
		}
	}
	
	function populateTreeNode() {
		//We only care what is below this node if it has children and is marked to be expanded
		//Sublists are always expanded
		if(($m_sublist || $m_expand) && $m_children) {
			
			$conn = db_connect();
			
			$query = "select * from header where parent = '".$postid."' order by posted";
			
			$result = $conn->query($query);
			
			for ($count=0; $row=@$result->fetch_assoc(); $count++) {
				if($m_sublist || $m_expanded[$row['postid']] == ture) {
					$expand = true;
				} else {
					$expand = false;
				}
				$this->m_childlist[$count] = new treenode ($row['postId'],
				$row['poster'], $row['postId'], $row['children'], $m_expand, $m_depth+1, $m_expanded, $m_sublist, false, $m_scopeid);
			}
		}
	}
	
	function display($row, $sublist = false) {
		//This method is responsible for display this class itself
		//$row tells us what row of the display we are up to so we know what color it should be
		
		//$sublist tells us whether we are on the main page or the message page. Message pages should have $sublist = true
		//On a sublist, all messages are exanded and there are no "+" or "-" symbols
		
		//If this is an empty node, skip displaying
		if($this->m_depth>-1) {
			echo "<tr><td bgcolor=\"";
			if ($row%2) {
				echo "#BDBDBD\">";
			} else {
				echo "#ffffff\">";
			}
			
			//indent replies to the depth of nesting
			for ($i=0; $i<$this->m_depth; $i++) {
				echo "<img src=\"image/spacer.gif\" height=\"22\" width=\"22\" alt=\"\" valign=\"bottom\" />";
			}
			
			//display + or - or a spacer
			if ((!$sublist) && ($this->m_children) && (sizeof($this->m_childlist))) {
				//we'are on the main page, have some children and they are expanded
				
				//we are expanded - offer button to collapse
				echo "<a href=\"index.php?collapse=".$this->m_postid."#".$this->m_postid."\">
						<img src=\"images/minus.gif\" valign=\"bottom\" height=\"22\" width=\"22\" alt=\"Collapse Thread\" border=\"0\" /></a>\n";
			}  else if(!$sublist && $this->m_children) {
				// we are collapsed - offer button to expand
				echo "<a href = 'index.php?expand=".
				$this->m_postid."#$this->m_postid'><img src = 'images/plus.gif'  
				height = 22 width = 22 alt = 'Expand Thread' border = 0></a>";
			} else {
				// we have no children, or are in a sublist, do not give button
				echo "<img src = 'images/spacer.gif' height = 22 width = 22 
                   alt = '' valign = 'bottom' />";
			}
			$msgBody = get_post($this->m_postid);

			//echo " <a name = $this->m_postid ><a href = 
			//		'view_post.php?postid=$this->m_postid'>$this->m_poster : ".$this->m_posted." ".$msgBody['message']."</a></td></tr>";
			echo " <a name = $this->m_postid >$this->m_poster : ".$msgBody['message']."</a>";

			//delete button for deleting current record
			if (isset($_SESSION['valid_user'])) {
				if ($this->m_poster == $_SESSION['valid_user'] || "75000g" == $_SESSION['valid_user']) {
					echo "<a href=\"index.php?delete=".$this->m_postid."\">
						<img class=\"deletebtn1\" src=\"sign_delete_icon.png\" align=\"right\" height=\"30px\" width=\"3%\" alt=\"Collapse Thread\" border=\"0\" /><img class=\"deletebtn2\" src=\"sign_delete_icon2.png\" align=\"right\" height=\"100%\" width=\"3%\" alt=\"Collapse Thread\" border=\"0\" /></a></td></tr>";
				}
			}
			
			// increment row counter to alternate colors
			$row++;
		}
		// call display on each of this node's children
		// note a node will only have children in its list if expanded
		$num_children = sizeof($this->m_childlist);
		for($i = 0; $i<$num_children; $i++)
		{
			$row = $this->m_childlist[$i]->display($row, $sublist);
		}
		return $row;
	}
}

?>
<?php
    class Paginator{
        public $items_per_page;
        public $items_total;
        public $currentPage;
        public $num_pages;
        public $mid_range;
        public $low;
        public $high;
        public $limit;
        public $return;
        public $default_ipp = 25;
        public $querystring;
        public $ipp_array;

        public function Paginator()
        {
            $this->currentPage = 1;
            $this->ipp_array = array(10, 25, 50, 100, 'Todo');
            $this->mid_range = 7;
            $this->items_per_page = (!empty($_GET['ipp'])) ? $_GET['ipp'] : $this->default_ipp;
        }

        public function paginate()
        {
            if ($_GET['ipp'] == 'Todo') {
                $this->num_pages = ceil($this->items_total / $this->default_ipp);

                $this->items_per_page = $this->default_ipp;
            } else {
                if (!is_numeric($this->items_per_page) or $this->items_per_page <= 0) {
                    $this->items_per_page = $this->default_ipp;
                }

                $this->num_pages = ceil($this->items_total / $this->items_per_page);
            }

            $this->currentPage = (int) $_GET['page']; // must be numeric > 0

            if ($this->currentPage < 1 or ! is_numeric($this->currentPage)) {
                $this->currentPage = 1;
            }

            if ($this->currentPage > $this->num_pages) {
                $this->currentPage = $this->num_pages;
            }

            $prev_page = $this->currentPage - 1;

            $next_page = $this->currentPage + 1;



            if ($_GET) {
                $args = explode("&", $_SERVER['QUERY_STRING']);

                foreach ($args as $arg) {
                    $keyval = explode("=", $arg);

                    if ($keyval[0] != "page" and $keyval[0] != "ipp") {
                        $this->querystring .= "&" . $arg;
                    }
                }
            }



            if ($_POST) {
                foreach ($_POST as $key => $val) {
                    if ($key != "page" and $key != "ipp") {
                        $this->querystring .= "&$key=$val";
                    }
                }
            }



            if ($this->num_pages > 10) {
                $this->return = ($this->currentPage != 1 and $this->items_total >= 10) ? "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$prev_page&ipp=$this->items_per_page$this->querystring\">&laquo; Anterior</a> " : "<span class=\"inactive\" href=\"#\">&laquo; Anterior</span> ";



                $this->start_range = $this->currentPage - floor($this->mid_range / 2);

                $this->end_range = $this->currentPage + floor($this->mid_range / 2);



                if ($this->start_range <= 0) {
                    $this->end_range += abs($this->start_range) + 1;

                    $this->start_range = 1;
                }

                if ($this->end_range > $this->num_pages) {
                    $this->start_range -= $this->end_range - $this->num_pages;

                    $this->end_range = $this->num_pages;
                }

                $this->range = range($this->start_range, $this->end_range);



                for ($i = 1; $i <= $this->num_pages; $i++) {
                    if ($this->range[0] > 2 and $i == $this->range[0]) {
                        $this->return .= " ... ";
                    }

                    // loop through all pages. if first, last, or in range, display

                    if ($i == 1 or $i == $this->num_pages or in_array($i, $this->range)) {
                        $this->return .= ($i == $this->currentPage and $_GET['page'] != 'Todo') ? "<a title=\"Ir a la p&aacute;gina $i de $this->num_pages\" class=\"current\" href=\"#\">$i</a> " : "<a class=\"paginate\" title=\"Ir a la p&aacute;gina $i de $this->num_pages\" href=\"$_SERVER[PHP_SELF]?page=$i&ipp=$this->items_per_page$this->querystring\">$i</a> ";
                    }

                    if ($this->range[$this->mid_range - 1] < $this->num_pages - 1 and $i == $this->range[$this->mid_range - 1]) {
                        $this->return .= " ... ";
                    }
                }

                $this->return .= (($this->currentPage != $this->num_pages and $this->items_total >= 10) and ($_GET['page'] != 'Todo')) ? "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$next_page&ipp=$this->items_per_page$this->querystring\">Siguiente &raquo;</a>\n" : "<span class=\"inactive\" href=\"#\">&raquo; Siguiente</span>\n";

                $this->return .= ($_GET['page'] == 'Todo') ? "<a class=\"current\" style=\"margin-left:10px\" href=\"#\">Todo</a> \n" : "<a class=\"paginate\" style=\"margin-left:10px\" href=\"$_SERVER[PHP_SELF]?page=1&ipp=Todo$this->querystring\">Todos</a> \n";
            } else {
                for ($i = 1; $i <= $this->num_pages; $i++) {
                    $this->return .= ($i == $this->currentPage) ? "<a class=\"current\" href=\"#\">$i</a> " : "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$i&ipp=$this->items_per_page$this->querystring\">$i</a> ";
                }

                $this->return .= "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=1&ipp=Todo$this->querystring\">Todo</a> \n";
            }

            $this->low = ($this->currentPage - 1) * $this->items_per_page;

            $this->high = ($_GET['ipp'] == 'Todo') ? $this->items_total : ($this->currentPage * $this->items_per_page) - 1;

            $this->limit = ($_GET['ipp'] == 'Todo') ? "" : " LIMIT $this->low,$this->items_per_page";
        }

        public function display_items_per_page()
        {
            $items = '';

    //        $ipp_array = array(10, 25, 50, 100, 'Todo');

            foreach ($this->ipp_array as $ipp_opt) {
                $items .= ($ipp_opt == $this->items_per_page) ? "<option selected value=\"$ipp_opt\">$ipp_opt</option>\n" : "<option value=\"$ipp_opt\">$ipp_opt</option>\n";
            }

            return "<span class=\"paginate\">Filas por p&aacute;gina:</span><select class=\"paginate\" onchange=\"window.location='$_SERVER[PHP_SELF]?page=1&ipp='+this[this.selectedIndex].value+'$this->querystring';return false\">$items</select>\n";
        }

        public function display_jump_menu()
        {
            $option = "";
            for ($i = 1; $i <= $this->num_pages; $i++) {
                $option .= ($i == $this->currentPage) ? "<option value=\"$i\" selected>$i</option>\n" : "<option value=\"$i\">$i</option>\n";
            }

            return "<span class=\"paginate\">P&aacute;gina:</span><select class=\"paginate\" onchange=\"window.location='$_SERVER[PHP_SELF]?page='+this[this.selectedIndex].value+'&ipp=$this->items_per_page$this->querystring';return false\">$option</select>\n";
        }

        public function display_pages()
        {
            return $this->return;
        }
    }
?>
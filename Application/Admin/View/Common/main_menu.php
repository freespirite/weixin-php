                    
                    <div id="sidebar" class="sidebar">
                        <div class="sidebar-menu nav-collapse">
                            <!-- SIDEBAR QUICK-LAUNCH -->
                            <!-- <div id="quicklaunch">
                            <!-- /SIDEBAR QUICK-LAUNCH -->

                            <!-- SIDEBAR MENU -->
                            <ul>
                                <?php
                                foreach(C('MENU_ARRAY') as $k => $v) {
                                    $hasSub = isset($v['sub'])? TRUE: FALSE;
                                    echo '<li id="c'.$k.'"'.($hasSub? ' class="has-sub"': '').'>';
                                    $link = '<i class="fa '.$v['flag'].' fa-fw"></i>'
                                            . '<span class="menu-text">'.$v['title'].'</span><span class="arrow"></span>';
                                    
                                    if($hasSub) {
                                        echo '<a href="javascript:;" class="">'.$link.'</a>';
                                        echo '<ul class="sub">';
                                        foreach($v['sub'] as $ks => $vs) {
                                            echo '<li id="c'.$k.'_'.$ks.'"><a href="'.U('admin/'.$k.'/'.$ks).'"><span class="sub-menu-text">'.$vs['title'].'</span></a></li>';
                                        }
                                        echo '</ul></li>';
                                    }
                                    else {
                                        echo '<a href="'.U('admin/'.$k.'/index').'">'.$link.'</a></li>';
                                    }
                                }
                                ?>
                                
                            </ul>
                            <!-- /SIDEBAR MENU -->
                        </div>
                    </div>

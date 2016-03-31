                    
                    <div id="sidebar" class="sidebar">
                        <div class="sidebar-menu nav-collapse">
                            <!-- SIDEBAR QUICK-LAUNCH -->
                            <!-- <div id="quicklaunch">
                            <!-- /SIDEBAR QUICK-LAUNCH -->

                            <!-- SIDEBAR MENU -->
                            <ul>
                                <?php
//                                echo CONTROLLER_NAME.'>>'.ACTION_NAME;exit;
                                $controllerName = strtolower(CONTROLLER_NAME);
                                $actionName = strtolower(ACTION_NAME);
                                foreach(C('MENU_ARRAY') as $k => $v) {
                                    $hasSub = isset($v['sub'])? TRUE: FALSE;
                                    $class  = $hasSub? 'has-sub': '';
                                    $class .= $k == $controllerName? ($class? ' ': '').'active': '';
                                    $class  = $class? ' class="'.$class.'"': '';
                                    echo '<li id="c'.$k.'"'.$class.'>';
                                    $link = '<i class="fa '.$v['flag'].' fa-fw"></i>'
                                            . '<span class="menu-text">'.$v['title'].'</span><span class="arrow"></span>';
                                    
                                    if($hasSub) {
                                        echo '<a href="javascript:;" class="">'.$link.'</a>';
                                        echo '<ul class="sub">';
                                        foreach($v['sub'] as $ks => $vs) {
                                            $aryKs = explode(',', $ks);
                                            $class = $k==$controllerName && in_array($actionName, $aryKs)? ' class="current"': ''; 
                                            echo '<li id="c'.$k.'_'.$aryKs[0].'"'.$class.'><a href="'.U('admin/'.$k.'/'.$aryKs[0]).'"><span class="sub-menu-text">'.$vs['title'].'</span></a></li>';
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

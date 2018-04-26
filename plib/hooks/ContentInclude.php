<?php
// Copyright 1999-2018. Plesk International GmbH.

/**
 * Class Modules_WelcomeWp_ContentInclude
 */
class Modules_WelcomeJoomla_ContentInclude extends pm_Hook_ContentInclude
{
    public function init()
    {
        if (pm_Session::isExist()) {
            if (pm_Session::getClient()->isAdmin()) {
                $status = pm_Settings::get('active', 1);

                if (!empty($status)) {
                    $head = new Zend_View_Helper_HeadLink();
                    $head->headLink()->appendStylesheet(pm_Context::getBaseUrl() . 'styles.css');

                    $page_loaded = $_SERVER['REQUEST_URI'];
                    $white_list = Modules_WelcomeJoomla_Helper::getWhiteListPages();

                    if (Modules_WelcomeJoomla_Helper::addMessage()) {
                        if (in_array($page_loaded, $white_list)) {
                            $client_name = pm_Session::getClient()->getProperty('pname');

                            if (empty($client_name)) {
                                $client_name = pm_Session::getClient()->getProperty('login');
                            }

                            $content = pm_Locale::lmsg('message_introtext', [
                                'close'      => '/modules/welcome-joomla/images/close.png',
                                'close_link' => pm_Context::getActionUrl('index', 'deactivate'),
                                'elvis'      => '/modules/welcome-joomla/images/elvis-plesky-joomla' . mt_rand(1, 2) . '.png',
                                'name'       => $client_name
                            ]);

                            if (Modules_WelcomeJoomla_Helper::checkAvailableDomains() == false) {
                                $content .= pm_Locale::lmsg('message_step_domain', [
                                    'link_domain' => Modules_WelcomeJoomla_Helper::getLinkNewDomain()
                                ]);
                            } else {
                                $white_list_os = Modules_WelcomeJoomla_Helper::stepListOs();
                                $step = pm_Settings::get('welcome-step', 1);

                                if ($step == 1) {
                                    if (Modules_WelcomeJoomla_Helper::isInstalled('joomla-toolkit')) {
                                        if (Modules_WelcomeJoomla_Helper::isInstalled('site-import')) {
                                            $content .= pm_Locale::lmsg('message_step_install_full', [
                                                'link_install' => '/modules/joomla-toolkit/index.php/install/index',
                                                'link_migrate' => '/modules/site-import/index.php/site-migration/new-migration'
                                            ]);
                                        } else {
                                            $content .= pm_Locale::lmsg('message_step_install_new', [
                                                'link_install'             => pm_Context::getActionUrl('index', 'redirect-custom-wp-install'),
                                                'link_install_site_import' => pm_Context::getActionUrl('index', 'install') . '?extension=site-import'
                                            ]);
                                        }
                                    } else {
                                        $content .= pm_Locale::lmsg('message_step_install_not_wptoolkit', [
                                            'link_install' => Modules_WelcomeJoomla_Helper::getExtensionCatalogLink('joomla-toolkit')
                                        ]);
                                    }

                                    if (in_array(Modules_WelcomeJoomla_Helper::getAdvisorData(), $white_list_os)) {
                                        $content .= pm_Locale::lmsg('message_step_ssl_inactive', [
                                            'class'             => 'todo',
                                            'link_advisor_name' => Modules_WelcomeJoomla_Helper::getAdvisorData('name')
                                        ]);
                                    }

                                    if (in_array('pagespeed-insights', $white_list_os)) {
                                        $content .= pm_Locale::lmsg('message_step_pagespeed_inactive', [
                                            'class' => 'todo'
                                        ]);
                                    }

                                    $content .= pm_Locale::lmsg('message_step_next', [
                                        'link_next'       => pm_Context::getActionUrl('index', 'step'),
                                        'link_deactivate' => pm_Context::getActionUrl('index', 'deactivate')
                                    ]);
                                } elseif ($step == 2) {
                                    if (in_array('joomla-toolkit', $white_list_os)) {
                                        $content .= pm_Locale::lmsg('message_step_install_inactive', [
                                            'class' => 'complete'
                                        ]);
                                    }

                                    if (Modules_WelcomeJoomla_Helper::isInstalled(Modules_WelcomeJoomla_Helper::getAdvisorData())) {
                                        $content .= pm_Locale::lmsg('message_step_ssl', [
                                            'link_security'     => '/modules/' . Modules_WelcomeJoomla_Helper::getAdvisorData() . '/',
                                            'link_advisor_name' => Modules_WelcomeJoomla_Helper::getAdvisorData('name')
                                        ]);
                                    } else {
                                        $content .= pm_Locale::lmsg('message_step_ssl_not', [
                                            'link_install'      => pm_Context::getActionUrl('index', 'install') . '?extension=' . Modules_WelcomeJoomla_Helper::getAdvisorData(),
                                            'link_advisor_name' => Modules_WelcomeJoomla_Helper::getAdvisorData('name')
                                        ]);
                                    }

                                    if (in_array('pagespeed-insights', $white_list_os)) {
                                        $content .= pm_Locale::lmsg('message_step_pagespeed_inactive', [
                                            'class' => 'todo'
                                        ]);
                                    }

                                    $content .= pm_Locale::lmsg('message_step_next', [
                                        'link_next'       => pm_Context::getActionUrl('index', 'step'),
                                        'link_deactivate' => pm_Context::getActionUrl('index', 'deactivate')
                                    ]);
                                } elseif ($step == 3) {
                                    if (in_array('joomla-toolkit', $white_list_os)) {
                                        $content .= pm_Locale::lmsg('message_step_install_inactive', [
                                            'class' => 'complete'
                                        ]);
                                    }

                                    if (in_array(Modules_WelcomeJoomla_Helper::getAdvisorData(), $white_list_os)) {
                                        $content .= pm_Locale::lmsg('message_step_ssl_inactive', [
                                            'class'             => 'complete',
                                            'link_advisor_name' => Modules_WelcomeJoomla_Helper::getAdvisorData('name')
                                        ]);
                                    }

                                    if (Modules_WelcomeJoomla_Helper::isInstalled('pagespeed-insights')) {
                                        $content .= pm_Locale::lmsg('message_step_pagespeed', [
                                            'link_pagespeed' => '/modules/pagespeed-insights/'
                                        ]);
                                    } else {
                                        $content .= pm_Locale::lmsg('message_step_pagespeed_not', [
                                            'link_install' => pm_Context::getActionUrl('index', 'install') . '?extension=pagespeed-insights'
                                        ]);
                                    }

                                    $content .= pm_Locale::lmsg('message_step_finish', [
                                        'link_finish' => pm_Context::getActionUrl('index', 'step'),
                                    ]);
                                } elseif ($step == 4) {
                                    $content .= pm_Locale::lmsg('message_step_restart', [
                                        'link_restart'    => pm_Context::getActionUrl('index', 'restart'),
                                        'link_deactivate' => pm_Context::getActionUrl('index', 'deactivate')
                                    ]);
                                }
                            }

                            $message = pm_Locale::lmsg('message_container', ['content' => $content]);

                            if (pm_View_Status::hasMessage($message) == false) {
                                pm_View_Status::addInfo($message, true);
                            }

                            pm_Settings::set('executed', time());
                        }
                    }
                }
            }
        }
    }
}

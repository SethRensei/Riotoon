<?php

use Riotoon\Entity\Category;
use Riotoon\Repository\CategoryRepository;

$repository = new CategoryRepository();

/** @var Category */
$categories = $repository->findAll();
?>
<footer class="footer-section">
    <div class="container">
        <div class="footer-cta pt-5 pb-5">
            <div class="row">
                <div class="col-xl-4 col-md-4 mb-30">
                    <div class="single-cta">
                        <i class="fas fa-phone"></i>
                        <div class="cta-text">
                            <h4>Nous Contacter</h4>
                            <a href="tel:+242067507958"><span>+242 06 750 79 58</span></a><br>
                            <a href="tel:+22955728609"><span>+229 55 72 86 09</span></a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 mb-30">
                    <div class="single-cta">
                        <i class="far fa-envelope-open"></i>
                        <div class="cta-text">
                            <h4>Ecrivez-nous</h4>
                            <a href="mailto:riotoon.contact@gmail.com"
                                target="_blank"><span>riotoon.contact@gmail.com</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-content user-select-none pt-5 pb-5">
            <div class="row">
                <div class="col-xl-4 col-lg-4 mb-50">
                    <div class="footer-widget">
                        <div class="footer-logo">
                            <a href="<?= $router->url('home') ?>"><img src="<?= BASE_URL ?>favicons/logo.svg"
                                    class="img-fluid" alt="logo"></a>
                        </div>
                        <div class="footer-text">
                            <p>RioToon est une application de dessin animé qui vous permet de télécharger et de lire vos
                                webtoons préférés.
                                <br> Qui est le meilleur endroit pour tous les amateurs de webtoons coréens.
                                <br> La meilleure façon de lire vos webtoons préférés sur votre appareil mobile.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 mb-30">
                    <div class="footer-widget">
                        <div class="footer-widget-heading">
                            <h3>Genres</h3>
                        </div>
                        <ul>
                            <?php foreach ($categories as $category): ?>
                                <li><a <?= isset($label) && $label == goodURL($category->getLabel()) ? 'class="active"' : '' ?>
                                        href="<?= $router->url('category', ['id' => $category->getId(), 'label' => goodURL($category->getLabel())]) ?>"><?= $category->getLabel() ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="col-xl-6 col-lg-6 text-center text-lg-left">
                        <div id="back-to-top" class="back-to-top">
                            <button class="btn btn-blue" title="Back to Top">
                                <i class="fa fa-angle-up"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-area">
        <div class="container">
            <div class="text-center text-lg-left">
                <div class="copyright-text">
                    <p><strong style="font-size: 23px;">&copy;</strong> Copyright <?= date('Y'); ?>, Tous droits
                        réservés</p>
                </div>
            </div>
        </div>
    </div>
</footer>
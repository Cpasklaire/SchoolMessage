/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/utils/_reset.scss';
import './styles/utils/_variables.scss';

import './styles/composants/_bouton.scss';
import './styles/composants/_header.scss';
import './styles/composants/_overlay.scss';

import './styles/layouts/_home.scss';
import './styles/layouts/_form.scss';
import './styles/layouts/_list.scss';

// start the Stimulus application
import './bootstrap';

<?php
if (!defined('ABSPATH')) { exit; }

function spanishnova_get_grammar_editor_template() {
    return <<<'HTML'
<p class="sn-intro">[Write a short introduction to the lesson.]</p>

<section id="overview">
<h2>Overview</h2>
<p>[Explain the main idea in a few sentences.]</p>
</section>

<section id="forms">
<h2>Forms</h2>
<p>[Add the key pattern or a table of forms.]</p>

<table class="compact-table">
<thead>
<tr>
<th>Subject</th>
<th>Form</th>
<th>Example</th>
<th>Translation</th>
</tr>
</thead>
<tbody>
<tr>
<td>[Subject]</td>
<td>[Form]</td>
<td>[Spanish example]</td>
<td>[English translation]</td>
</tr>
</tbody>
</table>
</section>

<section id="uses">
<h2>Uses</h2>
<p>[Explain when learners use this structure.]</p>
</section>

<section id="examples">
<h2>Examples</h2>
<ul class="sentence-list">
<li><strong>[Spanish example.]</strong> — <em>[English translation.]</em></li>
</ul>
</section>

<section id="practice">
<h2>Practice</h2>
<p>[Add varied practice that uses the lesson in context.]</p>
</section>
HTML;
}

function spanishnova_set_default_grammar_content($content, $post) {
    if (!$post instanceof WP_Post || 'grammar' !== $post->post_type || '' !== $content) {
        return $content;
    }

    return spanishnova_get_grammar_editor_template();
}
add_filter('default_content', 'spanishnova_set_default_grammar_content', 10, 2);

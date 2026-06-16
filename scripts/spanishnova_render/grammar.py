from html import escape


SUBJECT_ORDER = [
    "Yo",
    "Tu",
    "El / Ella / Usted",
    "Nosotros / Nosotras",
    "Ellos / Ellas / Ustedes",
]

ANSWER_GROUP_LABELS = {
    "select": "Selección simple",
    "complete": "Completar",
    "translate": "Traducción",
}


def md_pair(item):
    return f"{item['spanish']} - {item['english']}"


def html_pair(item):
    return f"<strong>{escape(item['spanish'])}</strong> - {escape(item['english'])}"


def md_arrow_pair(item):
    return f"{item['spanish']} -> {item['english']}"


def html_arrow_pair(item):
    return f"<strong>{escape(item['spanish'])}</strong> &#8594; <em>{escape(item['english'])}</em>"


def render_markdown_particle_set(row, data):
    lines = [
        data["intro"],
        "",
        "## Overview",
        "",
        data["overview"]["body"],
        "",
    ]

    for example in data["examples"]:
        lines.append(f"- {md_arrow_pair(example)}")

    lines += ["", "## Formas", ""]
    for item in data["forms"]:
        lines.append(f"- {item['label']} -> {item['meaning']}")

    if data.get("notes"):
        lines += ["", "### Notas", ""]
        for note in data["notes"]:
            lines.append(f"- {note}")

    lines += ["", "## Cómo/cuándo lo usamos", ""]
    for use in data["uses"]:
        lines += [f"### {use['heading']}", "", use["body"], ""]
        for example in use["examples"]:
            lines.append(f"- {md_arrow_pair(example)}")
        lines.append("")

    if data.get("forms_table"):
        headers = data["forms_table"]["headers"]
        lines += [
            "| " + " | ".join(headers) + " |",
            "| " + " | ".join(["---"] * len(headers)) + " |",
        ]
        for item in data["forms_table"]["rows"]:
            lines.append("| " + " | ".join(item) + " |")
        lines.append("")

    lines += ["## Oraciones.", "", "### Afirmativas", ""]
    for example in data["sentences"]["affirmative"]:
        lines.append(f"- {md_arrow_pair(example)}")
    lines += ["", "### Negativas", ""]
    for example in data["sentences"]["negative"]:
        lines.append(f"- {md_arrow_pair(example)}")
    lines += ["", "### Forma de pregunta", ""]
    for example in data["sentences"]["questions"]:
        lines.append(f"- {md_arrow_pair(example)}")

    lines += ["", "## Ejercicios", ""]

    if data["exercises"].get("select"):
        lines += ["### Selección simple", ""]
        for index, item in enumerate(data["exercises"]["select"], start=1):
            lines.append(f"{index}. {item['prompt']}")
            for option_index, option in zip(["a", "b", "c", "d"], item["options"]):
                lines.append(f"   - {option_index}) {option}")
            lines += ["", f"   {item['answer']}", ""]

    lines += ["### Completar", ""]
    for index, item in enumerate(data["exercises"]["complete"], start=1):
        lines += [f"{index}. {item['prompt']}", "", f"   {item['answer']}", ""]

    lines += ["### Traducción", ""]
    for index, item in enumerate(data["exercises"]["translate"], start=1):
        lines += [f"{index}. {item['prompt']}", "", f"   {item['answer']}", ""]

    wrap_up = data.get("wrap_up", [])
    if wrap_up:
        lines += [
            "## Wrap Up",
            "",
            "| " + " | ".join(data.get("wrap_up_headers", ["Use", "Example", "Translation"])) + " |",
            "| --- | --- | --- |",
        ]
        for item in wrap_up:
            lines.append(f"| {item['use']} | {item['example']} | {item['translation']} |")

    return "\n".join(lines).rstrip() + "\n"


def render_markdown(row, data):
    if row.get("lesson_type") == "particle-set":
        return render_markdown_particle_set(row, data)

    lines = [
        f"# {row['public_title']}",
        "",
        data["intro"],
        "",
        "## Overview",
        "",
        data["overview"]["body"],
        "",
    ]

    for example in data["examples"]:
        lines.append(f"- {md_pair(example)}")

    is_comparison = row.get("lesson_type") == "comparison"

    if is_comparison:
        lines += ["", "## Cómo/cuándo lo usamos", ""]
        for use in data["uses"]:
            lines += [f"### {use['heading']}", "", use["body"], ""]
            for example in use["examples"]:
                lines.append(f"- {md_pair(example)}")
            lines.append("")

    if data.get("structure"):
        structure_heading = "Comparación" if is_comparison else "Structure"
        lines += [
            "",
            f"## {structure_heading}",
            "",
            "| Structure | Example | Translation |",
            "| --- | --- | --- |",
        ]
        for item in data["structure"]:
            lines.append(f"| {item['pattern']} | {item['example']} | {item['translation']} |")

    if not is_comparison:
        lines += [
            "",
            "## Conjugation",
            "",
            "| Subject | Form | Example | Translation |",
            "| --- | --- | --- | --- |",
        ]
        for item in data["conjugation"]:
            lines.append(f"| {item['subject']} | {item['form']} | {item['example']} | {item['translation']} |")

        if row.get("lesson_type") == "verb-usage":
            lines += [
                "",
                "## Conjugation Practice Source Marker",
                "",
                "`sn-conjugation-practice`",
            ]

        lines += ["", "## Uses", ""]
        for use in data["uses"]:
            lines += [f"### {use['heading']}", "", use["body"], ""]
            for example in use["examples"]:
                lines.append(f"- {md_pair(example)}")
            lines.append("")

    question_heading = "Preguntas" if is_comparison else "Forma de pregunta"
    question_level = "##" if is_comparison else "###"
    lines += ["## Afirmativas" if is_comparison else "## Oraciones.", ""]
    if not is_comparison:
        lines += ["### Afirmativas", ""]
    for example in data["sentences"]["affirmative"]:
        lines.append(f"- {md_pair(example)}")
    lines += ["", "## Negativas" if is_comparison else "### Negativas", ""]
    for example in data["sentences"]["negative"]:
        lines.append(f"- {md_pair(example)}")
    lines += ["", f"{question_level} {question_heading}", ""]
    for example in data["sentences"]["questions"]:
        lines.append(f"- {md_pair(example)}")

    lines += ["", "## Ejercicios", ""]

    if data["exercises"].get("select"):
        lines += ["### Selección simple", ""]
        for index, item in enumerate(data["exercises"]["select"], start=1):
            lines.append(f"{index}. {item['prompt']}")
            for option_index, option in zip(["a", "b", "c", "d"], item["options"]):
                lines.append(f"   - {option_index}) {option}")
            lines += ["", f"   {item['answer']}", ""]

    lines += ["### Completar", ""]
    for index, item in enumerate(data["exercises"]["complete"], start=1):
        lines += [f"{index}. {item['prompt']}", "", f"   {item['answer']}", ""]

    lines += ["### Traducción", ""]
    for index, item in enumerate(data["exercises"]["translate"], start=1):
        lines += [f"{index}. {item['prompt']}", "", f"   {item['answer']}", ""]

    lines += ["## Answer Key", ""]
    for group in ["select", "complete", "translate"]:
        if group not in data["answers"]:
            continue
        lines += [f"### {ANSWER_GROUP_LABELS[group]}", ""]
        for index, answer in enumerate(data["answers"][group], start=1):
            lines.append(f"{index}. {answer}")
        lines.append("")

    wrap_up = data.get("wrap_up", [])
    if wrap_up:
        lines += [
            "## Wrap Up",
            "",
            "| Use | Example | Translation |",
            "| --- | --- | --- |",
        ]
        for item in wrap_up:
            lines.append(f"| {item['use']} | {item['example']} | {item['translation']} |")

    return "\n".join(lines).rstrip() + "\n"


def render_table(headers, rows, mark_last_column_as_translation=True):
    lines = [
        '  <table class="compact-table">',
        "    <thead>",
        "      <tr>",
    ]
    for header in headers:
        lines.append(f"        <th>{escape(header)}</th>")
    lines += [
        "      </tr>",
        "    </thead>",
        "    <tbody>",
    ]
    for row in rows:
        lines.append("      <tr>")
        for index, cell in enumerate(row):
            class_attr = ' class="translation"' if mark_last_column_as_translation and index == len(row) - 1 else ""
            lines.append(f"        <td{class_attr}>{escape(cell)}</td>")
        lines.append("      </tr>")
    lines += [
        "    </tbody>",
        "  </table>",
    ]
    return "\n".join(lines)


def render_example_list(items, list_class):
    lines = [f'      <ul class="{list_class}">']
    for item in items:
        lines.append(f"        <li>{html_pair(item)}</li>")
    lines.append("      </ul>")
    return "\n".join(lines)


def render_exercise_details(item):
    return f"<details><summary>Respuesta</summary><p>{escape(item['answer'])}</p></details>"


def render_particle_forms(items):
    lines = ['  <ul class="forms-list">']
    for item in items:
        lines.append(f"    <li><strong>{escape(item['label'])}</strong> &#8594; {escape(item['meaning'])}</li>")
    lines.append("  </ul>")
    return "\n".join(lines)


def render_particle_notes(items):
    if not items:
        return ""
    lines = ["  <h3>Notas</h3>"]
    for item in items:
        lines.append(f"  <p>{escape(item)}</p>")
    return "\n".join(lines)


def render_particle_examples(items):
    lines = ['  <ul class="sentence-list">']
    for item in items:
        lines.append(f"    <li>{html_arrow_pair(item)}</li>")
    lines.append("  </ul>")
    return "\n".join(lines)


def render_html_particle_set(row, data):
    parts = [
        f'<p class="intro">{escape(data["intro"])}</p>',
        "",
        '<section id="overview">',
        "  <h2>Overview</h2>",
        f"  <p>{escape(data['overview']['body'])}</p>",
        render_particle_examples(data["examples"]),
        "</section>",
        "",
        '<section id="formas">',
        "  <h2>Formas</h2>",
        render_particle_forms(data["forms"]),
    ]
    notes = render_particle_notes(data.get("notes", []))
    if notes:
        parts.append(notes)
    parts += [
        "</section>",
        "",
        '<section id="usos">',
        "  <h2>Cómo/cuándo lo usamos</h2>",
    ]
    for use in data["uses"]:
        parts += [
            f"  <h3>{escape(use['heading'])}</h3>",
            f"  <p>{escape(use['body'])}</p>",
            render_particle_examples(use["examples"]),
        ]
    if data.get("forms_table"):
        parts.append(
            render_table(
                data["forms_table"]["headers"],
                data["forms_table"]["rows"],
                mark_last_column_as_translation=False,
            )
        )
    parts += [
        "</section>",
        "",
        '<section id="oraciones">',
        "  <h2>Oraciones.</h2>",
        "  <h3>Afirmativas</h3>",
        render_particle_examples(data["sentences"]["affirmative"]),
        "  <h3>Negativas</h3>",
        render_particle_examples(data["sentences"]["negative"]),
        "  <h3>Forma de pregunta</h3>",
        render_particle_examples(data["sentences"]["questions"]),
        "</section>",
        "",
        '<section id="ejercicios">',
        "  <h2>Ejercicios</h2>",
    ]
    if data["exercises"].get("select"):
        parts += [
            '  <div class="exercise-block">',
            '    <h3 class="exercise-title">Selección simple</h3>',
            '    <ol class="exercise-list">',
        ]
        for item in data["exercises"]["select"]:
            parts.append(f"      <li><p>{escape(item['prompt'])}</p><ol type=\"a\">")
            for option in item["options"]:
                parts.append(f"        <li>{escape(option)}</li>")
            parts.append(f"      </ol>{render_exercise_details(item)}</li>")
        parts += [
            "    </ol>",
            "  </div>",
        ]
    parts += [
        '  <div class="exercise-block">',
        '    <h3 class="exercise-title">Completar</h3>',
        '    <ol class="exercise-list">',
    ]
    for item in data["exercises"]["complete"]:
        parts.append(f"      <li><p>{escape(item['prompt'])}</p>{render_exercise_details(item)}</li>")
    parts += [
        "    </ol>",
        "  </div>",
        '  <div class="exercise-block">',
        '    <h3 class="exercise-title">Traducción</h3>',
        '    <ol class="exercise-list">',
    ]
    for item in data["exercises"]["translate"]:
        parts.append(f"      <li><p>{escape(item['prompt'])}</p>{render_exercise_details(item)}</li>")
    parts += [
        "    </ol>",
        "  </div>",
        "</section>",
    ]

    if data.get("wrap_up"):
        parts += [
            "",
            '<section id="wrap-up">',
            "  <h2>Wrap Up</h2>",
            render_table(
                data.get("wrap_up_headers", ["Use", "Example", "Translation"]),
                [[item["use"], item["example"], item["translation"]] for item in data["wrap_up"]],
            ),
            "</section>",
        ]

    return "\n".join(parts).rstrip() + "\n"


def render_html(row, data):
    if row.get("lesson_type") == "particle-set":
        return render_html_particle_set(row, data)

    lead_example = data["examples"][0]
    breadcrumb = f"Grammar / {row.get('grammar_tax', '').strip() or row['topic_base']} / {row['topic_base']}"
    is_comparison = row.get("lesson_type") == "comparison"
    parts = [
        '<section class="lesson-hero">',
        f'  <div class="breadcrumb">{escape(breadcrumb)}</div>',
        '  <div class="meta-row">',
        "    <span class=\"pill\">Grammar</span>",
        f"    <span class=\"pill\">{escape(row.get('level_tax', '').strip())}</span>",
        f"    <span class=\"pill\">{escape(row['topic_base'])}</span>",
        "  </div>",
        f"  <h1>{escape(row['public_title'])}</h1>",
        f'  <p class="intro">{escape(data["intro"])}</p>',
        "</section>",
        "",
        '<section class="panel" id="overview">',
        "  <h2>Overview</h2>",
        f"  <p>{escape(data['overview']['body'])}</p>",
        '  <div class="simple-example">',
        '    <div class="example-line">',
        f"      <strong>{escape(lead_example['spanish'])}</strong>",
        '      <span class="example-arrow">-&gt;</span>',
        f"      <em>{escape(lead_example['english'])}</em>",
        "    </div>",
        f'    <div class="use-line"><span class="use-label">Use:</span> {escape(data["overview"]["use_note"])}</div>',
        "  </div>",
        "</section>",
    ]

    if is_comparison:
        parts += [
            "",
            '<section class="panel" id="uses">',
            "  <h2>Cómo/cuándo lo usamos</h2>",
            '  <div class="two-column">',
        ]
        for use in data["uses"]:
            parts += [
                '    <div class="example-group">',
                f"      <h3>{escape(use['heading'])}</h3>",
                f"      <p>{escape(use['body'])}</p>",
                render_example_list(use["examples"], "sentence-list"),
                "    </div>",
            ]
        parts += ["  </div>", "</section>"]

    if data.get("structure"):
        structure_heading = "Comparación" if is_comparison else "Structure"
        parts += [
            "",
            '<section class="panel" id="structure">',
            f"  <h2>{structure_heading}</h2>",
            render_table(
                ["Structure", "Example", "Translation"],
                [[item["pattern"], item["example"], item["translation"]] for item in data["structure"]],
            ),
            "</section>",
        ]

    if not is_comparison:
        parts += [
            "",
            '<section class="panel" id="conjugation">',
            "  <h2>Conjugation</h2>",
            render_table(
                ["Subject", "Form", "Example", "Translation"],
                [[item["subject"], item["form"], item["example"], item["translation"]] for item in data["conjugation"]],
            ),
        ]
        if row.get("lesson_type") == "verb-usage":
            parts.append('  <p class="sn-conjugation-practice">sn-conjugation-practice</p>')
        parts.append("</section>")

        parts += [
            "",
            '<section class="panel" id="uses">',
            "  <h2>Uses</h2>",
            '  <div class="two-column">',
        ]
        for use in data["uses"]:
            parts += [
                '    <div class="example-group">',
                f"      <h3>{escape(use['heading'])}</h3>",
                f"      <p>{escape(use['body'])}</p>",
                render_example_list(use["examples"], "sentence-list"),
                "    </div>",
            ]
        parts += ["  </div>", "</section>"]

    if is_comparison:
        parts += [
            "",
            '<section class="panel" id="afirmativas">',
            "  <h2>Afirmativas</h2>",
            render_example_list(data["sentences"]["affirmative"], "sentence-list"),
            "</section>",
            "",
            '<section class="panel" id="negativas">',
            "  <h2>Negativas</h2>",
            render_example_list(data["sentences"]["negative"], "sentence-list"),
            "</section>",
            "",
            '<section class="panel" id="preguntas">',
            "  <h2>Preguntas</h2>",
            render_example_list(data["sentences"]["questions"], "question-list"),
            "</section>",
        ]
    else:
        parts += [
            "",
            '<section class="panel" id="oraciones">',
            "  <h2>Oraciones.</h2>",
            '  <div class="two-column">',
            '    <div class="example-group">',
            "      <h3>Afirmativas</h3>",
            render_example_list(data["sentences"]["affirmative"], "sentence-list"),
            "    </div>",
            '    <div class="example-group">',
            "      <h3>Negativas</h3>",
            render_example_list(data["sentences"]["negative"], "sentence-list"),
            "    </div>",
            "  </div>",
            '  <div class="example-bubble">',
            "    <h3>Forma de pregunta</h3>",
            render_example_list(data["sentences"]["questions"], "question-list"),
            "  </div>",
            "</section>",
        ]

    parts += [
        "",
        '<section class="panel" id="ejercicios">',
        "  <h2>Ejercicios</h2>",
    ]
    if data["exercises"].get("select"):
        parts += [
            '  <div class="exercise-block">',
            '    <h3 class="exercise-title">Selección simple</h3>',
            '    <ol class="exercise-list">',
        ]
        for item in data["exercises"]["select"]:
            parts.append(f"      <li><p>{escape(item['prompt'])}</p><ol type=\"a\">")
            for option in item["options"]:
                parts.append(f"        <li>{escape(option)}</li>")
            parts.append(f"      </ol>{render_exercise_details(item)}</li>")
        parts += [
            "    </ol>",
            "  </div>",
        ]
    parts += [
        '  <div class="exercise-block">',
        '    <h3 class="exercise-title">Completar</h3>',
        '    <ol class="exercise-list">',
    ]
    for item in data["exercises"]["complete"]:
        parts.append(f"      <li><p>{escape(item['prompt'])}</p>{render_exercise_details(item)}</li>")
    parts += [
        "    </ol>",
        "  </div>",
    ]
    parts += [
        '  <div class="exercise-block">',
        '    <h3 class="exercise-title">Traducción</h3>',
        '    <ol class="exercise-list">',
    ]
    for item in data["exercises"]["translate"]:
        parts.append(f"      <li><p>{escape(item['prompt'])}</p>{render_exercise_details(item)}</li>")
    parts += [
        "    </ol>",
        "  </div>",
        "</section>",
    ]

    if data.get("wrap_up"):
        parts += [
            "",
            '<section class="panel" id="wrap-up">',
            "  <h2>Wrap Up</h2>",
            render_table(
                ["Use", "Example", "Translation"],
                [[item["use"], item["example"], item["translation"]] for item in data["wrap_up"]],
            ),
            "</section>",
        ]

    return "\n".join(parts).rstrip() + "\n"

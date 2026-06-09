from html import escape


SUBJECT_ORDER = [
    "Yo",
    "Tu",
    "El / Ella / Usted",
    "Nosotros / Nosotras",
    "Ellos / Ellas / Ustedes",
]

ANSWER_GROUP_LABELS = {
    "select": "Seleccionar",
    "complete": "Completar",
    "yes_no": "Preguntas de si/no",
    "translate": "Traducir",
}


def md_pair(item):
    return f"{item['spanish']} - {item['english']}"


def html_pair(item):
    return f"<strong>{escape(item['spanish'])}</strong> - {escape(item['english'])}"


def render_markdown(row, data):
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

    if data.get("structure"):
        lines += [
            "",
            "## Structure",
            "",
            "| Structure | Example | Translation |",
            "| --- | --- | --- |",
        ]
        for item in data["structure"]:
            lines.append(f"| {item['pattern']} | {item['example']} | {item['translation']} |")

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

    lines += ["## Oraciones", "", "### Afirmativa", ""]
    for example in data["sentences"]["affirmative"]:
        lines.append(f"- {md_pair(example)}")
    lines += ["", "### Negativa", ""]
    for example in data["sentences"]["negative"]:
        lines.append(f"- {md_pair(example)}")
    lines += ["", "### Preguntas", ""]
    for example in data["sentences"]["questions"]:
        lines.append(f"- {md_pair(example)}")

    lines += ["", "## Ejercicios", ""]

    if data["exercises"].get("select"):
        lines += ["### Seleccionar", ""]
        for index, item in enumerate(data["exercises"]["select"], start=1):
            lines.append(f"{index}. {item['prompt']}")
            for option_index, option in zip(["a", "b", "c", "d"], item["options"]):
                lines.append(f"   - {option_index}) {option}")
            lines += ["", f"   {item['answer']}", ""]

    lines += ["### Completar", ""]
    for index, item in enumerate(data["exercises"]["complete"], start=1):
        lines += [f"{index}. {item['prompt']}", "", f"   {item['answer']}", ""]

    if data["exercises"].get("yes_no"):
        lines += ["### Preguntas de si/no", ""]
        for index, item in enumerate(data["exercises"]["yes_no"], start=1):
            lines += [f"{index}. {item['prompt']}", "", f"   {item['answer']}", ""]

    lines += ["### Traducir", ""]
    for index, item in enumerate(data["exercises"]["translate"], start=1):
        lines += [f"{index}. {item['prompt']}", "", f"   {item['answer']}", ""]

    lines += ["## Answer Key", ""]
    for group in ["select", "complete", "yes_no", "translate"]:
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


def render_table(headers, rows):
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
            class_attr = ' class="translation"' if index == len(row) - 1 else ""
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


def render_html(row, data):
    lead_example = data["examples"][0]
    breadcrumb = f"Grammar / {row.get('grammar_tax', '').strip() or row['topic_base']} / {row['topic_base']}"
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

    if data.get("structure"):
        parts += [
            "",
            '<section class="panel" id="structure">',
            "  <h2>Structure</h2>",
            render_table(
                ["Structure", "Example", "Translation"],
                [[item["pattern"], item["example"], item["translation"]] for item in data["structure"]],
            ),
            "</section>",
        ]

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

    parts += [
        "",
        '<section class="panel" id="oraciones">',
        "  <h2>Oraciones</h2>",
        '  <div class="two-column">',
        '    <div class="example-group">',
        "      <h3>Afirmativa</h3>",
        render_example_list(data["sentences"]["affirmative"], "sentence-list"),
        "    </div>",
        '    <div class="example-group">',
        "      <h3>Negativa</h3>",
        render_example_list(data["sentences"]["negative"], "sentence-list"),
        "    </div>",
        "  </div>",
        '  <div class="example-bubble">',
        "    <h3>Preguntas</h3>",
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
            '    <h3 class="exercise-title">Seleccionar</h3>',
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
    if data["exercises"].get("yes_no"):
        parts += [
            '  <div class="exercise-block">',
            '    <h3 class="exercise-title">Preguntas de si/no</h3>',
            '    <ol class="exercise-list">',
        ]
        for item in data["exercises"]["yes_no"]:
            parts.append(f"      <li><p>{escape(item['prompt'])}</p>{render_exercise_details(item)}</li>")
        parts += [
            "    </ol>",
            "  </div>",
        ]
    parts += [
        '  <div class="exercise-block">',
        '    <h3 class="exercise-title">Traducir</h3>',
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

from html import escape


ANSWER_GROUP_LABELS = {
    "select": "Selección simple",
    "complete": "Completar",
    "translate": "Traducción",
}


def should_render_structure(lesson_type):
    return lesson_type in {"structure", "comparison"}


def should_render_conjugation(lesson_type):
    return lesson_type in {"tense", "verb-usage"}


def structure_heading_for(lesson_type):
    if lesson_type == "comparison":
        return "Comparación"
    return "Estructura"


def structure_id_for(lesson_type):
    if lesson_type == "comparison":
        return "comparacion"
    return "estructura"


def structure_items_for(data, lesson_type):
    if lesson_type == "comparison":
        return data.get("comparison") or data.get("structure") or []
    return data.get("structure") or []


def md_pair(item):
    return f"{item['spanish']} -> {item['english']}"


def html_pair(item):
    return f"<strong>{escape(item['spanish'])}</strong> &#8594; <em>{escape(item['english'])}</em>"


def render_markdown_table(headers, rows):
    lines = [
        "| " + " | ".join(headers) + " |",
        "| " + " | ".join(["---"] * len(headers)) + " |",
    ]
    for row in rows:
        lines.append("| " + " | ".join(row) + " |")
    return lines


def render_markdown(row, data):
    lesson_type = row.get("lesson_type", "").strip()
    lines = [
        data["intro"],
        "",
        "## Overview",
        "",
        data["overview"]["body"],
        "",
    ]

    for example in data["examples"]:
        lines.append(f"- {md_pair(example)}")

    if data.get("forms"):
        lines += ["", "## Formas", ""]
        for item in data["forms"]:
            lines.append(f"- {item['label']} -> {item['meaning']}")
        if data.get("notes"):
            lines += ["", "### Notas", ""]
            for note in data["notes"]:
                lines.append(f"- {note}")

    if data.get("conjugation") and should_render_conjugation(lesson_type):
        lines += [
            "",
            "## Conjugación",
            "",
            *render_markdown_table(
                ["Subject", "Forma", "Example", "Translation"],
                [[item["subject"], item["form"], item["example"], item["translation"]] for item in data["conjugation"]],
            ),
        ]
        if lesson_type == "verb-usage":
            lines += ["", "`sn-conjugation-practice`"]

    lines += ["", "## Cómo/cuándo lo usamos", ""]
    for use in data["uses"]:
        lines += [f"### {use['heading']}", "", use["body"], ""]
        for example in use["examples"]:
            lines.append(f"- {md_pair(example)}")
        lines.append("")

    if data.get("forms_table"):
        lines += render_markdown_table(data["forms_table"]["headers"], data["forms_table"]["rows"])
        lines.append("")

    structure_items = structure_items_for(data, lesson_type)
    if structure_items and should_render_structure(lesson_type):
        heading = structure_heading_for(lesson_type)
        lines += [
            f"## {heading}",
            "",
            *render_markdown_table(
                ["Patrón", "Example", "Translation"],
                [[item["pattern"], item["example"], item["translation"]] for item in structure_items],
            ),
            "",
        ]

    lines += ["## Oraciones.", "", "### Afirmativas", ""]
    for example in data["sentences"]["affirmative"]:
        lines.append(f"- {md_pair(example)}")
    lines += ["", "### Negativas", ""]
    for example in data["sentences"]["negative"]:
        lines.append(f"- {md_pair(example)}")
    lines += ["", "### Forma de pregunta", ""]
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

    if data.get("wrap_up"):
        lines += [
            "## Wrap Up",
            "",
            *render_markdown_table(
                data.get("wrap_up_headers", ["Use", "Example", "Translation"]),
                [[item["use"], item["example"], item["translation"]] for item in data["wrap_up"]],
            ),
        ]

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


def render_example_list(items, list_class="sentence-list"):
    lines = [f'  <ul class="{list_class}">']
    for item in items:
        lines.append(f"    <li>{html_pair(item)}</li>")
    lines.append("  </ul>")
    return "\n".join(lines)


def render_exercise_details(item):
    return f"<details><summary>Respuesta</summary><p>{escape(item['answer'])}</p></details>"


def render_forms(items):
    lines = ['  <ul class="forms-list">']
    for item in items:
        lines.append(f"    <li><strong>{escape(item['label'])}</strong> &#8594; {escape(item['meaning'])}</li>")
    lines.append("  </ul>")
    return "\n".join(lines)


def render_notes(items):
    if not items:
        return ""
    lines = ["  <h3>Notas</h3>"]
    for item in items:
        lines.append(f"  <p>{escape(item)}</p>")
    return "\n".join(lines)


def render_exercises(data):
    parts = [
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
        parts += ["    </ol>", "  </div>"]

    parts += [
        '  <div class="exercise-block">',
        '    <h3 class="exercise-title">Completar</h3>',
        '    <ol class="exercise-list">',
    ]
    for item in data["exercises"]["complete"]:
        parts.append(f"      <li><p>{escape(item['prompt'])}</p>{render_exercise_details(item)}</li>")
    parts += ["    </ol>", "  </div>"]

    parts += [
        '  <div class="exercise-block">',
        '    <h3 class="exercise-title">Traducción</h3>',
        '    <ol class="exercise-list">',
    ]
    for item in data["exercises"]["translate"]:
        parts.append(f"      <li><p>{escape(item['prompt'])}</p>{render_exercise_details(item)}</li>")
    parts += ["    </ol>", "  </div>", "</section>"]
    return parts


def render_html(row, data):
    lesson_type = row.get("lesson_type", "").strip()
    parts = [
        f'<p class="intro">{escape(data["intro"])}</p>',
        "",
        '<section id="overview">',
        "  <h2>Overview</h2>",
        f"  <p>{escape(data['overview']['body'])}</p>",
        render_example_list(data["examples"]),
        "</section>",
    ]

    if data.get("forms"):
        parts += [
            "",
            '<section id="formas">',
            "  <h2>Formas</h2>",
            render_forms(data["forms"]),
        ]
        notes = render_notes(data.get("notes", []))
        if notes:
            parts.append(notes)
        parts.append("</section>")

    if data.get("conjugation") and should_render_conjugation(lesson_type):
        parts += [
            "",
            '<section id="conjugacion">',
            "  <h2>Conjugación</h2>",
            render_table(
                ["Subject", "Forma", "Example", "Translation"],
                [[item["subject"], item["form"], item["example"], item["translation"]] for item in data["conjugation"]],
            ),
        ]
        if lesson_type == "verb-usage":
            parts.append('  <p class="sn-conjugation-practice">sn-conjugation-practice</p>')
        parts.append("</section>")

    parts += [
        "",
        '<section id="usos">',
        "  <h2>Cómo/cuándo lo usamos</h2>",
    ]
    for use in data["uses"]:
        parts += [
            f"  <h3>{escape(use['heading'])}</h3>",
            f"  <p>{escape(use['body'])}</p>",
            render_example_list(use["examples"]),
        ]
    if data.get("forms_table"):
        parts.append(
            render_table(
                data["forms_table"]["headers"],
                data["forms_table"]["rows"],
                mark_last_column_as_translation=False,
            )
        )
    parts.append("</section>")

    structure_items = structure_items_for(data, lesson_type)
    if structure_items and should_render_structure(lesson_type):
        heading = structure_heading_for(lesson_type)
        section_id = structure_id_for(lesson_type)
        parts += [
            "",
            f'<section id="{section_id}">',
            f"  <h2>{heading}</h2>",
            render_table(
                ["Patrón", "Example", "Translation"],
                [[item["pattern"], item["example"], item["translation"]] for item in structure_items],
            ),
            "</section>",
        ]

    parts += [
        "",
        '<section id="oraciones">',
        "  <h2>Oraciones.</h2>",
        "  <h3>Afirmativas</h3>",
        render_example_list(data["sentences"]["affirmative"]),
        "  <h3>Negativas</h3>",
        render_example_list(data["sentences"]["negative"]),
        "  <h3>Forma de pregunta</h3>",
        render_example_list(data["sentences"]["questions"], "question-list"),
        "</section>",
        "",
        *render_exercises(data),
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

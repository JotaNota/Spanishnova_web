from html import escape


def validate_vocabulary_data(data):
    if not isinstance(data, dict):
        raise SystemExit("vocabulary content-data must be an object")
    if not str(data.get("intro", "")).strip():
        raise SystemExit("vocabulary content-data intro must be a non-empty string")
    if not section_groups(data):
        raise SystemExit("vocabulary content-data must include vocabulary, categories, sections, or word_groups")


def section_groups(data):
    for key in ["vocabulary", "categories", "sections", "word_groups"]:
        value = data.get(key)
        if isinstance(value, list) and value:
            return value
    return []


def title_for(row, data):
    return str(data.get("title") or row.get("public_title") or row.get("base_slug") or "").strip()


def section_title(section):
    return str(section.get("heading") or section.get("title") or section.get("category") or "Vocabulary").strip()


def section_items(section):
    items = section.get("items") or section.get("words") or section.get("terms") or []
    return items if isinstance(items, list) else []


def item_spanish(item):
    if isinstance(item, str):
        return item
    return str(item.get("spanish") or item.get("term") or item.get("word") or item.get("phrase") or "").strip()


def item_english(item):
    if isinstance(item, str):
        return ""
    return str(item.get("english") or item.get("translation") or item.get("meaning") or "").strip()


def pair_markdown(item):
    spanish = item_spanish(item)
    english = item_english(item)
    if english:
        return f"- **{spanish}** -> {english}"
    return f"- **{spanish}**"


def pair_html(item):
    spanish = escape(item_spanish(item))
    english = escape(item_english(item))
    if english:
        return f"    <li><strong>{spanish}</strong> &#8594; <em>{english}</em></li>"
    return f"    <li><strong>{spanish}</strong></li>"


def sentence_items(data):
    for key in ["phrases", "sentences", "common_phrases"]:
        value = data.get(key)
        if isinstance(value, list) and value:
            return key, value
    return None, []


def exercise_groups(data):
    exercises = data.get("exercises")
    if not isinstance(exercises, dict):
        return []
    return [(key, value) for key, value in exercises.items() if isinstance(value, list) and value]


def render_markdown(row, data):
    lines = [
        f"# {title_for(row, data)}",
        "",
        str(data["intro"]).strip(),
        "",
        "## Vocabulary",
        "",
    ]

    for section in section_groups(data):
        lines += [f"### {section_title(section)}", ""]
        for item in section_items(section):
            lines.append(pair_markdown(item))
        lines.append("")

    phrase_label, phrases = sentence_items(data)
    if phrases:
        heading = "Common Phrases" if phrase_label else "Phrases"
        lines += [f"## {heading}", ""]
        for item in phrases:
            lines.append(pair_markdown(item))
        lines.append("")

    fun_phrase = data.get("fun_phrase")
    if isinstance(fun_phrase, dict) and item_spanish(fun_phrase):
        lines += ["## Fun Phrase", "", pair_markdown(fun_phrase).removeprefix("- "), ""]

    groups = exercise_groups(data)
    if groups:
        lines += ["## Exercises", ""]
        for group_name, items in groups:
            lines += [f"### {group_name.replace('_', ' ').title()}", ""]
            for index, item in enumerate(items, start=1):
                if isinstance(item, dict):
                    prompt = str(item.get("prompt") or item.get("english") or item.get("spanish") or "").strip()
                    answer = str(item.get("answer") or item.get("translation") or "").strip()
                else:
                    prompt = str(item).strip()
                    answer = ""
                lines += [f"{index}. {prompt}", ""]
                if answer:
                    lines += [f"   {answer}", ""]

    questions = data.get("questions")
    if isinstance(questions, list) and questions:
        lines += ["## Questions", ""]
        for index, question in enumerate(questions, start=1):
            lines.append(f"{index}. {question}")
        lines.append("")

    return "\n".join(lines).rstrip() + "\n"


def render_html(row, data):
    parts = [
        f'<p class="intro">{escape(str(data["intro"]).strip())}</p>',
        "",
        '<section id="vocabulary">',
        "  <h2>Vocabulary</h2>",
    ]

    for section in section_groups(data):
        parts += [
            f"  <h3>{escape(section_title(section))}</h3>",
            '  <ul class="sentence-list">',
        ]
        parts.extend(pair_html(item) for item in section_items(section))
        parts.append("  </ul>")
    parts.append("</section>")

    _, phrases = sentence_items(data)
    if phrases:
        parts += ["", '<section id="common-phrases">', "  <h2>Common Phrases</h2>", '  <ul class="sentence-list">']
        parts.extend(pair_html(item) for item in phrases)
        parts += ["  </ul>", "</section>"]

    fun_phrase = data.get("fun_phrase")
    if isinstance(fun_phrase, dict) and item_spanish(fun_phrase):
        parts += [
            "",
            '<section id="fun-phrase">',
            "  <h2>Fun Phrase</h2>",
            f"  <p>{pair_html(fun_phrase).strip()[4:-5]}</p>",
            "</section>",
        ]

    groups = exercise_groups(data)
    if groups:
        parts += ["", '<section id="exercises">', "  <h2>Exercises</h2>"]
        for group_name, items in groups:
            parts += [f"  <h3>{escape(group_name.replace('_', ' ').title())}</h3>", '  <ol class="exercise-list">']
            for item in items:
                if isinstance(item, dict):
                    prompt = escape(str(item.get("prompt") or item.get("english") or item.get("spanish") or "").strip())
                    answer = escape(str(item.get("answer") or item.get("translation") or "").strip())
                else:
                    prompt = escape(str(item).strip())
                    answer = ""
                details = f"<details><summary>Respuesta</summary><p>{answer}</p></details>" if answer else ""
                parts.append(f"    <li><p>{prompt}</p>{details}</li>")
            parts.append("  </ol>")
        parts.append("</section>")

    questions = data.get("questions")
    if isinstance(questions, list) and questions:
        parts += ["", '<section id="questions">', "  <h2>Questions</h2>", '  <ol class="question-list">']
        for question in questions:
            parts.append(f"    <li>{escape(str(question))}</li>")
        parts += ["  </ol>", "</section>"]

    return "\n".join(parts).rstrip() + "\n"

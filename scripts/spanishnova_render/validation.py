import re


BASE_REQUIRED_GRAMMAR_FIELDS = [
    "intro",
    "overview",
    "examples",
    "uses",
    "sentences",
    "exercises",
    "answers",
]

GRAMMAR_REQUIRED_BY_LESSON_TYPE = {
    "tense": ["conjugation"],
    "verb-usage": ["conjugation"],
    "structure": [],
    "comparison": [],
    "particle-set": ["forms"],
}

GRAMMAR_REQUIRE_ONE_OF_BY_LESSON_TYPE = {
    "structure": ["structure", "forms"],
    "comparison": ["comparison", "structure"],
    "particle-set": ["forms", "forms_table"],
}

SPANISH_TEXT_FIELDS = {"spanish", "prompt", "answer", "example", "options"}
BROKEN_ENCODING_PATTERNS = [
    (re.compile(r"\ufffd"), "replacement character"),
    (re.compile(r"[ÃÂ]"), "UTF-8 mojibake marker"),
    (re.compile(r"\?T\?"), "broken Tú marker"),
    (re.compile(r"\bT\?"), "broken Tú marker"),
    (re.compile(r"\?l"), "broken él marker"),
    (re.compile(r"\?Qu"), "broken Qué marker"),
    (re.compile(r"\?D"), "broken Dónde/Cuándo marker"),
    (re.compile(r"\?C"), "broken Cómo/Cuándo/Cuál marker"),
    (re.compile(r"\?[A-ZÁÉÍÓÚÜÑ]"), "question mark used where opening ¿ is expected"),
]


def require_fields(data, fields, context):
    missing = [field for field in fields if field not in data]
    if missing:
        raise SystemExit(f"Missing required {context} field(s): {', '.join(missing)}")


def require_list(data, field, context):
    value = data.get(field)
    if not isinstance(value, list) or not value:
        raise SystemExit(f"{context}.{field} must be a non-empty list")
    return value


def require_exact_list(data, field, count, context):
    value = require_list(data, field, context)
    if len(value) != count:
        raise SystemExit(f"{context}.{field} must contain exactly {count} item(s)")
    return value


def require_dict(data, field, context):
    value = data.get(field)
    if not isinstance(value, dict):
        raise SystemExit(f"{context}.{field} must be an object")
    return value


def require_one_of(data, fields, context):
    if not any(field in data for field in fields):
        raise SystemExit(f"{context} must include one of: {', '.join(fields)}")


def validate_no_broken_spanish_text(value, path):
    if isinstance(value, dict):
        for key, child in value.items():
            child_path = f"{path}.{key}" if path else key
            if key in SPANISH_TEXT_FIELDS:
                validate_no_broken_spanish_text(child, child_path)
            elif isinstance(child, (dict, list)):
                validate_no_broken_spanish_text(child, child_path)
        return

    if isinstance(value, list):
        for index, child in enumerate(value, start=1):
            validate_no_broken_spanish_text(child, f"{path}[{index}]")
        return

    if not isinstance(value, str):
        return

    for pattern, label in BROKEN_ENCODING_PATTERNS:
        if pattern.search(value):
            raise SystemExit(f"Broken Spanish text encoding in {path}: {label}: {value}")


def validate_grammar_data(data, lesson_type=None):
    if lesson_type not in GRAMMAR_REQUIRED_BY_LESSON_TYPE:
        raise SystemExit(f"Unsupported grammar lesson_type: {lesson_type}")

    required_fields = BASE_REQUIRED_GRAMMAR_FIELDS + GRAMMAR_REQUIRED_BY_LESSON_TYPE[lesson_type]
    require_fields(data, required_fields, "grammar content-data")
    if lesson_type in GRAMMAR_REQUIRE_ONE_OF_BY_LESSON_TYPE:
        require_one_of(data, GRAMMAR_REQUIRE_ONE_OF_BY_LESSON_TYPE[lesson_type], "grammar content-data")

    if not isinstance(data.get("intro"), str) or not data["intro"].strip():
        raise SystemExit("grammar content-data intro must be a non-empty string")
    validate_no_broken_spanish_text(data, "grammar content-data")

    overview = require_dict(data, "overview", "grammar content-data")
    require_fields(overview, ["body", "use_note"], "grammar overview")
    require_list(data, "examples", "grammar content-data")

    if "forms" in data:
        require_list(data, "forms", "grammar content-data")
    if "forms_table" in data:
        forms_table = require_dict(data, "forms_table", "grammar content-data")
        require_fields(forms_table, ["headers", "rows"], "grammar forms_table")
        require_list(forms_table, "headers", "grammar forms_table")
        require_list(forms_table, "rows", "grammar forms_table")
    if "structure" in data:
        require_list(data, "structure", "grammar content-data")
    if "comparison" in data:
        require_list(data, "comparison", "grammar content-data")
    if "conjugation" in data:
        require_list(data, "conjugation", "grammar content-data")

    require_list(data, "uses", "grammar content-data")

    sentences = require_dict(data, "sentences", "grammar content-data")
    require_fields(sentences, ["affirmative", "negative", "questions"], "grammar sentences")
    for group in ["affirmative", "negative", "questions"]:
        require_list(sentences, group, "grammar sentences")

    exercises = require_dict(data, "exercises", "grammar content-data")
    require_fields(exercises, ["select", "complete", "translate"], "grammar exercises")
    for group in ["select", "complete", "translate"]:
        items = require_list(exercises, group, "grammar exercises")
        for index, item in enumerate(items, start=1):
            require_fields(item, ["prompt", "answer"], f"grammar exercises.{group}[{index}]")
            if group == "select":
                require_list(item, "options", f"grammar exercises.{group}[{index}]")

    answers = require_dict(data, "answers", "grammar content-data")
    require_fields(answers, ["select", "complete", "translate"], "grammar answers")
    for group in ["select", "complete", "translate"]:
        require_list(answers, group, "grammar answers")

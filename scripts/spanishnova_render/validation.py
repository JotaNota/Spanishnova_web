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


def require_fields(data, fields, context):
    missing = [field for field in fields if field not in data]
    if missing:
        raise SystemExit(f"Missing required {context} field(s): {', '.join(missing)}")


def require_list(data, field, context):
    value = data.get(field)
    if not isinstance(value, list) or not value:
        raise SystemExit(f"{context}.{field} must be a non-empty list")
    return value


def require_dict(data, field, context):
    value = data.get(field)
    if not isinstance(value, dict):
        raise SystemExit(f"{context}.{field} must be an object")
    return value


def require_one_of(data, fields, context):
    if not any(field in data for field in fields):
        raise SystemExit(f"{context} must include one of: {', '.join(fields)}")


def validate_grammar_data(data, lesson_type=None):
    if lesson_type not in GRAMMAR_REQUIRED_BY_LESSON_TYPE:
        raise SystemExit(f"Unsupported grammar lesson_type: {lesson_type}")

    required_fields = BASE_REQUIRED_GRAMMAR_FIELDS + GRAMMAR_REQUIRED_BY_LESSON_TYPE[lesson_type]
    require_fields(data, required_fields, "grammar content-data")
    if lesson_type in GRAMMAR_REQUIRE_ONE_OF_BY_LESSON_TYPE:
        require_one_of(data, GRAMMAR_REQUIRE_ONE_OF_BY_LESSON_TYPE[lesson_type], "grammar content-data")

    if not isinstance(data.get("intro"), str) or not data["intro"].strip():
        raise SystemExit("grammar content-data intro must be a non-empty string")

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
    require_fields(exercises, ["complete", "translate"], "grammar exercises")
    for group in ["select", "complete", "translate"]:
        if group not in exercises:
            continue
        items = require_list(exercises, group, "grammar exercises")
        for index, item in enumerate(items, start=1):
            require_fields(item, ["prompt", "answer"], f"grammar exercises.{group}[{index}]")
            if group == "select":
                require_list(item, "options", f"grammar exercises.{group}[{index}]")

    answers = require_dict(data, "answers", "grammar content-data")
    require_fields(answers, ["complete", "translate"], "grammar answers")
    for group in ["select"]:
        if group in exercises:
            require_fields(answers, [group], "grammar answers")

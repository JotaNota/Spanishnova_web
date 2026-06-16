REQUIRED_GRAMMAR_FIELDS = [
    "intro",
    "overview",
    "examples",
    "conjugation",
    "uses",
    "sentences",
    "exercises",
    "answers",
]

REQUIRED_PARTICLE_SET_FIELDS = [
    "intro",
    "overview",
    "examples",
    "forms",
    "uses",
    "sentences",
    "exercises",
    "answers",
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


def require_dict(data, field, context):
    value = data.get(field)
    if not isinstance(value, dict):
        raise SystemExit(f"{context}.{field} must be an object")
    return value


def validate_grammar_data(data, lesson_type=None):
    is_particle_set = lesson_type == "particle-set"
    required_fields = REQUIRED_PARTICLE_SET_FIELDS if is_particle_set else REQUIRED_GRAMMAR_FIELDS
    require_fields(data, required_fields, "grammar content-data")
    if not isinstance(data.get("intro"), str) or not data["intro"].strip():
        raise SystemExit("grammar content-data intro must be a non-empty string")

    overview = require_dict(data, "overview", "grammar content-data")
    require_fields(overview, ["body", "use_note"], "grammar overview")
    require_list(data, "examples", "grammar content-data")
    if is_particle_set:
        require_list(data, "forms", "grammar content-data")
    else:
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

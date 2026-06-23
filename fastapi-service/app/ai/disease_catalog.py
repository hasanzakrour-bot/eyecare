# -*- coding: utf-8 -*-
"""
Canonical disease catalog for the retinal AI service.
The labels here are the single source of truth returned to Laravel.
"""

TARGET_DISEASES = {
    "healthy": {
        "en": "Healthy",
        "ar": "سليم",
        "risk": "low",
        "aliases": [
            "healthy", "normal", "no disease", "no finding", "no abnormality", "n"
        ],
    },
    "diabetic_retinopathy": {
        "en": "Diabetic Retinopathy",
        "ar": "اعتلال الشبكية السكري",
        "risk": "medium",
        "aliases": [
            "diabetic retinopathy", "diabetes", "dr", "retinopathy", "d"
        ],
    },
    "csr": {
        "en": "Central Serous Retinopathy",
        "ar": "اعتلال الشبكية المصلي المركزي",
        "risk": "medium",
        "aliases": [
            "central serous retinopathy", "central serous chorioretinopathy", "csr", "csc"
        ],
    },
    "disc_edema": {
        "en": "Optic Disc Edema",
        "ar": "وذمة القرص البصري",
        "risk": "high",
        "aliases": [
            "disc edema", "optic disc edema", "papilledema", "papilloedema", "ode"
        ],
    },
    "glaucoma": {
        "en": "Glaucoma",
        "ar": "المياه الزرقاء / الجلوكوما",
        "risk": "high",
        "aliases": [
            "glaucoma", "g"
        ],
    },
    "macular_scar": {
        "en": "Macular Scar",
        "ar": "ندبة البقعة العينية",
        "risk": "medium",
        "aliases": [
            "macular scar", "scar macular", "macular scarring", "chorioretinal scar"
        ],
    },
    "myopia": {
        "en": "Pathological Myopia",
        "ar": "قصر النظر الشديد",
        "risk": "medium",
        "aliases": [
            "myopia", "pathological myopia", "high myopia", "severe myopia", "m"
        ],
    },
    "retinal_detachment": {
        "en": "Retinal Detachment",
        "ar": "انفصال الشبكية",
        "risk": "critical",
        "aliases": [
            "retinal detachment", "detachment", "rd"
        ],
    },
    "retinitis_pigmentosa": {
        "en": "Retinitis Pigmentosa",
        "ar": "التهاب الشبكية الصباغي",
        "risk": "medium",
        "aliases": [
            "retinitis pigmentosa", "rp", "pigmentosa"
        ],
    },
}

RISK_PRIORITY = {
    "low": 1,
    "medium": 2,
    "high": 3,
    "critical": 4,
}

DR_STAGES = {
    "mild": {"en": "Mild DR", "ar": "خفيف"},
    "moderate": {"en": "Moderate DR", "ar": "متوسط"},
    "severe": {"en": "Severe DR", "ar": "شديد"},
    "proliferative": {"en": "Proliferative DR", "ar": "تكاثري"},
}

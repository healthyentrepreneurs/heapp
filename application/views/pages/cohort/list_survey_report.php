<script src="https://unpkg.com/jquery"></script>
<script src="https://unpkg.com/survey-jquery@1.8.18/survey.jquery.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.0.10/jspdf.plugin.autotable.min.js"></script>
<script type="text/javascript" src="https://oss.sheetjs.com/sheetjs/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/4.7.2/js/tabulator.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/tabulator/4.7.2/css/tabulator.min.css" />
<link href="https://unpkg.com/survey-analytics@1.8.18/survey.analytics.tabulator.css" rel="stylesheet" />
<script src="https://unpkg.com/survey-analytics@1.8.18/survey.analytics.tabulator.js"></script>
<div>
    <p></p>
</div>
<div id="loadingIndicator1">
    <span>
        <div id="loading">
            <strong>loading...</strong>
            <span></span></div>
    </span>
</div>
<div id="vizPanel1"></div>
<div id="surveyElement" style="display:inline-block;width:100%;"></div>
<div id="surveyResult"></div>
<script>
    $(document).ready(function() {
        var json = {
            "pages": [{
                    "name": "page1",
                    "elements": [{
                            "type": "radiogroup",
                            "name": "used_fp_method",
                            "title": "Have you ever used a modern family planning method?",
                            "description": "Okozesezzako enkola eyomulembe okutekateka ezadde.",
                            "isRequired": true,
                            "choices": [{
                                    "value": "item1",
                                    "text": "Yes / Yee"
                                },
                                {
                                    "value": "item2",
                                    "text": "No / Nedda"
                                }
                            ]
                        },
                        {
                            "type": "radiogroup",
                            "name": "using_fp_method",
                            "visibleIf": "{used_fp_method} = 'item1'",
                            "title": "Are you currently using a modern family planning method?",
                            "description": " Oli kunkola ey'omulembe ey'entekateka y'ezadde?",
                            "isRequired": true,
                            "choices": [{
                                    "value": "item1",
                                    "text": "Yes / Yee"
                                },
                                {
                                    "value": "item2",
                                    "text": "No / Nedda"
                                }
                            ]
                        }
                    ],
                    "title": "Family Planning"
                },
                {
                    "name": "page2",
                    "elements": [{
                            "type": "checkbox",
                            "name": "which_fp_method_used",
                            "visibleIf": "{used_fp_method} = 'item1'",
                            "title": "Which family method?",
                            "description": " Ntekateka kki ey'zadde?",
                            "choices": [{
                                    "value": "item1",
                                    "text": "Combined oral contraceptive / Empeke ezilimu ebirungo ebigatiddwa"
                                },
                                {
                                    "value": "item2",
                                    "text": "Progesterone only pills / Empeke ezilumu ekilungo ekimu"
                                },
                                {
                                    "value": "item3",
                                    "text": "Depo (injectable) / Empiso ya depo"
                                },
                                {
                                    "value": "item4",
                                    "text": "Sayana press / Empiso ya sayana"
                                },
                                {
                                    "value": "item5",
                                    "text": "Condoms / Obupiira"
                                },
                                {
                                    "value": "item6",
                                    "text": "IUD (Coil) / Akaweta"
                                },
                                {
                                    "value": "item7",
                                    "text": "Tubal ligation / Okusala ensekke"
                                },
                                {
                                    "value": "item8",
                                    "text": "LAM (Lactational amenorrhea) / Okuyonsa kwokka"
                                },
                                {
                                    "value": "item9",
                                    "text": "Calendar (cycle beads) /  Okubala enakku z'omweezi"
                                },
                                {
                                    "value": "item10",
                                    "text": "Implants"
                                }
                            ],
                            "hasOther": true,
                            "otherText": "Other (describe) / N'endala"
                        },
                        {
                            "type": "html",
                            "name": "Info2",
                            "visibleIf": "{used_fp_method} = 'item2'",
                            "html": "<hr><p>Educate the client about the family planning methods and possible side effects.<p>Options: Combined oral contraceptive / progesterone only pills / depo (injectable) / syana press / implants / condoms / IUD (coil) / tubal ligation / LAM (Lactational amenorrhea) / Calendar (cycle beads)</p></hr><hr>\n<p>Yigiriza buli azze okufuna obuwereza entekateka z'ezadde n'ebiyinza okutukako nga ozikozeseza.</p>\n\n\n<p>Empeke ezilimu ebirungo ebigatiddwa/Empeke ezilumu ekilungo ekimu/Empiso ya depo/Empiso ya sayana/implants/Obupiira/Akaweta/Okusala ensekke/Okuyonsa kwokka/Okubala enakku z'omweezi/n'endala</p>\n</hr>"
                        }
                    ]
                },
                {
                    "name": "page3",
                    "elements": [{
                        "type": "radiogroup",
                        "name": "partner_yesno",
                        "title": "Was the partner included in the health education session?",
                        "description": "Omwagalwa Yabadde mukuyigirizibwa?",
                        "isRequired": true,
                        "choices": [{
                                "value": "item1",
                                "text": "Yes / Yee"
                            },
                            {
                                "value": "item2",
                                "text": "No / Nedda"
                            },
                            {
                                "value": "item3",
                                "text": "Not applicable / Tekyetagisa"
                            }
                        ]
                    }]
                },
                {
                    "name": "page4",
                    "elements": [{
                            "type": "radiogroup",
                            "name": "accept_fp_method",
                            "visibleIf": "{used_fp_method} = 'item2' or {using_fp_method} = 'item2'",
                            "title": "Does the client accept to take a FP method?",
                            "description": "Eyazze akiriza entekareka y'ezadde ?",
                            "choices": [{
                                    "value": "item1",
                                    "text": "Yes / Yee"
                                },
                                {
                                    "value": "item2",
                                    "text": "No / Nedda"
                                }
                            ]
                        },
                        {
                            "type": "radiogroup",
                            "name": "switch_fp_method",
                            "visibleIf": "{using_fp_method} = 'item1'",
                            "title": "Does the client want to switch to another FP method? ",
                            "description": "Eyazze yandiyadde okudda kuntekateka y'ezadde endala?",
                            "choices": [{
                                    "value": "item1",
                                    "text": "Yes / Yee"
                                },
                                {
                                    "value": "item2",
                                    "text": "No / Nedda"
                                }
                            ]
                        }
                    ]
                },
                {
                    "name": "page5",
                    "elements": [{
                        "type": "html",
                        "name": "info5",
                        "visibleIf": "{accept_fp_method} = 'item2' or {switch_fp_method} = 'item2'",
                        "html": "<hr>Thank the client for their time, and follow-up after 30 days\n</hr><hr>Webazze azze olw'obudde,era omukeberekko oluvanyuma lw'enakku amakumi asatu.</hr>"
                    }]
                },
                {
                    "name": "page6",
                    "elements": [{
                            "type": "radiogroup",
                            "name": "choose_new_fp_method",
                            "visibleIf": "{switch_fp_method} = 'item1' or {accept_fp_method} = 'item1'",
                            "title": "Which FP method does the client want to use?",
                            "description": "Kika kki eky'entekateka y'ezadde  eyazze ky'ayagala?",
                            "choices": [{
                                    "value": "item1",
                                    "text": "Combined oral contraceptive / Empeke ezilimu ebirungo ebigatiddwa"
                                },
                                {
                                    "value": "item2",
                                    "text": "Progesterone only pills / Empeke ezilumu ekilungo ekimu"
                                },
                                {
                                    "value": "item3",
                                    "text": "Depo (injectable) / Empiso ya depo"
                                },
                                {
                                    "value": "item4",
                                    "text": "Sayana press / Empiso ya sayana"
                                },
                                {
                                    "value": "item5",
                                    "text": "Condoms / Obupiira"
                                },
                                {
                                    "value": "item6",
                                    "text": "IUD (Coil) / Akaweta"
                                },
                                {
                                    "value": "item7",
                                    "text": "Tubal ligation / Okusala ensekke"
                                },
                                {
                                    "value": "item8",
                                    "text": "LAM (Lactational amenorrhea) / Okuyonsa kwokka"
                                },
                                {
                                    "value": "item9",
                                    "text": "Calendar (cycle beads) /  Okubala enakku z'omweezi"
                                },
                                {
                                    "value": "item10",
                                    "text": "Implants"
                                }
                            ],
                            "hasOther": true,
                            "otherText": "Other (describe) / N'endala"
                        },
                        {
                            "type": "radiogroup",
                            "name": "oral_pills_given",
                            "visibleIf": "{choose_new_fp_method} = 'item1'",
                            "title": "Did you deliver the Combined oral contraception pills?",
                            "description": "Wawadde Empekke z'ebirungo ebibiri?",
                            "choices": [{
                                    "value": "item1",
                                    "text": "Yes / Yee"
                                },
                                {
                                    "value": "item2",
                                    "text": "No / Nedda"
                                }
                            ]
                        },
                        {
                            "type": "radiogroup",
                            "name": "condoms_given",
                            "visibleIf": "{choose_new_fp_method} = 'item5'",
                            "title": "Did you deliver the Condoms?",
                            "description": "Wawadde Bupiira?",
                            "choices": [{
                                    "value": "item1",
                                    "text": "Yes / Yee"
                                },
                                {
                                    "value": "item2",
                                    "text": "No / Nedda"
                                }
                            ]
                        },
                        {
                            "type": "radiogroup",
                            "name": "question1",
                            "visibleIf": "{choose_new_fp_method} = 'item4'",
                            "title": "Did you deliver the Sayana Press?",
                            "description": "Wawadde Sayana Press?",
                            "choices": [{
                                    "value": "item1",
                                    "text": "Yes / Yee"
                                },
                                {
                                    "value": "item2",
                                    "text": "No / Nedda"
                                }
                            ]
                        },
                        {
                            "type": "html",
                            "name": "info6-2",
                            "visibleIf": "{choose_new_fp_method} = 'item5' or {choose_new_fp_method} = 'item1' or {choose_new_fp_method} = 'item4'",
                            "html": "<hr>Remind the client that a follow-up needs to be done when refill is needed</hr><hr>Jjukiza azze nti okugobererwa kulina okolebwa w'aba alina okwongerayo empereza.</hr>\n"
                        },
                        {
                            "type": "html",
                            "name": "info6-3",
                            "visibleIf": "{choose_new_fp_method} = 'item2' or {choose_new_fp_method} = 'item3' or {choose_new_fp_method} = 'item6' or {choose_new_fp_method} = 'item7' or {choose_new_fp_method} = 'item8' or {choose_new_fp_method} = 'item9' or {choose_new_fp_method} = 'item10'",
                            "html": "<hr>Refer to the health facility for other options\n</hr><hr>Ddala muwereze kuddwaliro erikuli okumpi.</hr>\n"
                        }
                    ],
                    "visibleIf": "{accept_fp_method} = 'item1' or {switch_fp_method} = 'item1'"
                }
            ]
        };

        var survey = new Survey.Model(json);
        var allQuestions = survey.getAllQuestions();

        var panel1Node = document.getElementById("vizPanel1");
        panel1Node.innerHTML = "";

        $.get("https://surveyjs.io/api/MySurveys/getSurveyNPCResults/", function() {
            var data = {
                "used_fp_method": "item2",
                "partner_yesno": "item2",
                "accept_fp_method": "item1",
                "choose_new_fp_method": "item1",
                "oral_pills_given": "item1"
            }
            var surveyAnalyticsTabulator = new SurveyAnalyticsTabulator.Tabulator(survey, data);
            surveyAnalyticsTabulator.render(panel1Node);
            $("#loadingIndicator1").hide();
        });

    });
</script>
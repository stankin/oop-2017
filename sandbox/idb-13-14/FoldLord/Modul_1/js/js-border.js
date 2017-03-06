function ChangeBorder () {
            var selectTags = document.getElementsByTagName ("select");
            var selectState = [];
            
            for (i = 0; i < selectTags.length; i++) {
                // Returns the index of the selected option
                whichSelected = selectTags[i].selectedIndex;

                // Returns the text of the selected option
                selectState[i] = selectTags[i].options[whichSelected].text;
            }

            var div = document.getElementById ("myDiv");
            div.style.border = selectState[0] + " " + selectState[1] + " " + selectState[2];
        }
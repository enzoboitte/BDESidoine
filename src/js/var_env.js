Number.prototype.mod = function(b) {
    // Calculate
    return ((this % b) + b) % b;
}

function hash(message) {
    return CryptoJS.SHA256(message).toString(CryptoJS.enc.Hex);
}

function base64ToBytes(base64)
{
    const binString = atob(base64);
    return Uint8Array.from(binString, (m) => m.codePointAt(0));
}

function bytesToBase64(bytes)
{
    const binString = String.fromCodePoint(...bytes);
    return btoa(binString);
}

function F_feCallFile(l_sPath, l_sMethode="POST", l_lParams = {})
{
    const formData = new FormData();

    for (const l_sKey in l_lParams)
    {
        formData.append(l_sKey, l_lParams[l_sKey]);
    }

    console.log("F_feCallFile", l_sPath, l_sMethode, l_lParams);

    let f = l_sMethode === "POST" ? fetch(l_sPath, {
        method: l_sMethode,
        body: formData,
        headers: { 'Content-Type': 'application/json' }
    }) : fetch(l_sPath, {
        method: l_sMethode
    });

    return f
        .then(response => response.text())
        .catch(error =>
        {
            console.error('Erreur:', error);
        });
}

// fonction d'appel de lien api /v1/event/add/...,...,..... | /v1/event/rm/... | /v1/event/update/...,...,..... pour ajouter/supprimer/modifier les événements 
function CallApi(sMethod, sUrl, oData, formatText="json") 
{
    let l_cData = null;
    if(formatText === "json")
    {
        l_cData = JSON.stringify(oData);
    } else if(formatText === "url")
    {
        l_cData = new URLSearchParams(oData).toString();
    } if(formatText === "formData")
    {
        let data = new FormData();
        for (const key in oData) {
            if (oData.hasOwnProperty(key)) {
                data.append(key, oData[key]);
            }
        }
        l_cData = data;
    }


    return fetch(`${G_sPath}${sUrl}`, {
        method: sMethod,
        headers: {
            'Content-Type': 'application/json'
        },
        body: l_cData
    })
}
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
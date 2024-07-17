function setOrUnsetFavorite(recipeId, csrfToken)
{
    let url = '/recipe/favorite/'+recipeId;

    let formData = new FormData();
    formData.append('csrf', csrfToken);

    fetch(url, {
        method: "POST",
        headers: {
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
    })
//TODO catch error
    alert(recipeId);
    alert(csrfToken);
}

async function setOrUnsetFavorite(recipeId, csrfToken)
{
    //We add this function here to make the user experience smoother. So we can avoid having to wait for the server to answer
    setIsFavorite(recipeId);

    let url = '/recipe/favorite/'+recipeId;

    let formData = new FormData();
    formData.append('csrf', csrfToken);

    try{
        const response = await fetch(url, {
            method: "POST",
            headers: {
                'Accept': 'application/json',
            },
            body: formData
        })
        if (response.ok) {
            const data = await response.json();
            setIsFavorite(recipeId, data.isFavorite)
        } else {
            throw new Error(response.status);
        }
    }
    catch(error)
    {
        //TODO: alert error modal
        alert('An error occured, please try again later.');
        //We comeback to the initial state because there was an error on the backend
        setIsFavorite(recipeId);

    }
}

function setIsFavorite(recipeId, isFavorite = null)
{
    const btnLikeIcon = document.getElementById('recipe-like-icon-'+recipeId);
    const btnNotLikedIcon = document.getElementById('recipe-not-liked-icon-'+recipeId);

    const shouldFavorite = (isFavorite === null) ? btnLikeIcon.classList.contains('hide') : isFavorite;

    // Toggle visibility based on the determined favorite state
    if (shouldFavorite) {
        btnLikeIcon.classList.remove('hide');
        btnNotLikedIcon.classList.add('hide');
    } else {
        btnNotLikedIcon.classList.remove('hide');
        btnLikeIcon.classList.add('hide');
    }
}

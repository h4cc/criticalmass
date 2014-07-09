City = function()
{
};

City.prototype.id = null;
City.prototype.citySlug = null;
City.prototype.city = null;
City.prototype.title = null;
City.prototype.description = null;
City.prototype.latitude = null;
City.prototype.longitude = null;
City.prototype.tileLayerAddress = null;
City.prototype.tileLayerAttributation = null;

City.prototype.getId = function()
{
    return this.id;
};

City.prototype.setId = function(id)
{
    this.id = id;
};

City.prototype.getCitySlug = function()
{
    return this.citySlug;
};

City.prototype.setCitySlug = function(citySlug)
{
    this.citySlug = citySlug;
};

City.prototype.getCity = function()
{
    return this.city;
};

City.prototype.setCity = function(city)
{
    this.city = city;
};

City.prototype.getTitle = function()
{
    return this.title;
};

City.prototype.setTitle = function(title)
{
    this.title = title;
};

City.prototype.getDescription = function()
{
    return this.description;
};

City.prototype.setDescription = function(description)
{
    this.description = description;
};

City.prototype.getUrl = function()
{
    return this.url;
};

City.prototype.setUrl = function(url)
{
    this.url = url;
};

City.prototype.getFacebook = function()
{
    return this.facebook;
};

City.prototype.setFacebook = function(facebook)
{
    this.facebook = facebook;
};

City.prototype.getTwitter = function()
{
    return this.twitter;
};

City.prototype.setTwitter = function(twitter)
{
    this.twitter = twitter;
};

City.prototype.getLatitude = function()
{
    return this.latitude;
};

City.prototype.setLatitude = function(latitude)
{
    this.latitude = latitude;
};

City.prototype.getLongitude = function()
{
    return this.longitude;
};

City.prototype.setLongitude = function(longitude)
{
    this.longitude = longitude;
};

City.prototype.getTileLayerAddress = function()
{
    return this.tileLayerAddress;
};

City.prototype.setTileLayerAddress = function(tileLayerAddress)
{
    this.tileLayerAddress = tileLayerAddress;
};

City.prototype.getTileLayerAttributation = function()
{
    return this.tileLayerAttributation;
}

City.prototype.setTileLayerAttributation = function(tileLayerAttributation)
{
    this.tileLayerAttributation = tileLayerAttributation;
}

City.prototype.countSocialMediaLinks = function()
{
  var socialMediaCounter = 0;

    if (this.getUrl() != '')
    {
        ++socialMediaCounter;
    }

    if (this.getFacebook() != '')
    {
        ++socialMediaCounter;
    }

    if (this.getTwitter() != '')
    {
        ++socialMediaCounter;
    }

    return socialMediaCounter;
};

City.prototype.getLatLng = function()
{
    return L.latLng(this.latitude, this.longitude);
};
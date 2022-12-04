

    
    

  export default function url(no){
    var getUrl = window.location;
    var baseUrl =getUrl.pathname.split('/')[no];
    return baseUrl;
  }
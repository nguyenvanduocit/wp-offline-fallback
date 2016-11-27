if('serviceWorker' in navigator){
  navigator.serviceWorker.register('/sw.js').then(function(registation){
    console.log("ServiceWorker Registered.");
  });
}

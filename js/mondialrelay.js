var mondialrelay = (function($, window, document, undefined){
    return {
        run:function(merchant){
            // Loading the Parcelshop picker widget into the <div> with id "Zone_Widget" with such settings:
            $("#Zone_Widget").MR_ParcelShopPicker({
                //
                // Settings relating to the HTML.
                //
                // Selecteur de l'élément dans lequel est envoyé l'ID du Point Relais (ex: input hidden)
                Target: "#Target_Widget",
                // Selecteur de l'élément dans lequel est envoyé l'ID du Point Relais pour affichage
                TargetDisplay: "#TargetDisplay_Widget",
                // Selecteur de l'élément dans lequel sont envoysé les coordonnées complètes du point relais
                TargetDisplayInfoPR: "#TargetDisplayInfoPR_Widget",
                //
                // Settings for Parcelshop data access
                //
                // Code given by Mondial Relay, 8 characters (padding right with spaces)
                // BDTEST is used for development only => a warning appears
                Brand: merchant,
                // Default Country (2 letters) used for search at loading
                Country: "BE",
                // Default postal Code used for search at loading
                PostCode: "4500",
                // Delivery mode (Standard [24R], XL [24L], XXL [24X], Drive [DRI])
                ColLivMod: "24R",
                // Number of parcelshops requested (must be less than 20)
                NbResults: "7",
                //
                // Display settings
                //
                // Enable Responsive (nb: non responsive corresponds to the Widget used in older versions=
                Responsive: true,
                // Show the results on Leaflet map usng OpenStreetMap.
                ShowResultsOnMap: true,
                // Fonction de callback déclenché lors de la selection d'un Point Relais
                OnParcelShopSelected:
                // Fonction de traitement à la sélection du point relais.
                // Remplace les données de cette page par le contenu de la variable data.
                // data: les informations du Point Relais
                    function(data) {
                        $("#cb_ID").val(data.ID);
                        $("#cb_Nom").val(data.Nom);
                        $("#cb_Adresse").val(data.Adresse1);
                        $("#cb_CP").val(data.CP);
                        $("#cb_Ville").val(data.Ville);
                        $("#cb_Pays").val(data.Pays);
                    }
            });
        }
    }
})(jQuery, window, document);
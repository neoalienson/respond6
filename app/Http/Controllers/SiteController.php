<?php

namespace App\Http\Controllers;

use App\Respond\Database\Site;
use \Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Retrieve the user for the given ID.
     *
     * @return Response
     */
    public function test()
    {

        return '[Respond] API works!';

    }
    
    /**
     * Test the auth
     *
     * @return Response
     */
    public function testAuth(Request $request)
    {
    
        $siteId = $request->input('siteId');
        $userId = $request->input('userId');

        return '[Respond] Authorized, siteId='.$siteId.' and userId='.$userId;

    }
    
    

    /**
     * Validates the site id
     *
     * @return Response
     */
    public function validateId(Request $request)
    {

        $friendlyId = $request->input('friendlyId');

        $isFriendlyIdUnique = Site::IsFriendlyIdUnique($friendlyId);

        // check for reserved names
        if($friendlyId == 'app' || $friendlyId == 'sites' || $friendlyId == 'api') {
	        $isFriendlyIdUnique = false;
        }

        if($isFriendlyIdUnique==false) {

            // send conflict(409)
            return response('ID is not unique', 409);
        }
        else {

	        // return 200
            return response('Ok', 200);

        }

    }

}
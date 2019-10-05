<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Category;
use Carbon\Carbon;
use App\User;
use App\Article;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\ArticleStoreRequest;

class APIController extends Controller
{
    public function signup(UserStoreRequest $request)
    {

        $request->validated();
try{
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        return response()->json(['message' => 'Successfully created user!'], 201);

    } catch(Exception $e) {
        return response()->json(['message' => 'something went error','status'=>401]);
      }
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);


        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized','status'=>401]);


        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

/**
 *function of addCategory insert category in database after validation
 *
 */
    public function addCategory(CategoryStoreRequest $request)
    {
         $request->validated();
try{
        $input = $request->all();
        $input['parent_id'] = empty($input['parent_id']) ? null : $input['parent_id'];

        Category::create($input);
        return response()->json(['message' => 'category created successfully'], 200);
    } catch(Exception $e) {
        return response()->json(['message' => 'something went error','status'=>401]);
      }

    }

/**
 *  function of AllCategories return json data of all categories and its childs
 * */

    public function AllCategories()
    {

        try{
        $allCategories = Category::where('parent_id',null)->get();

        foreach($allCategories as $category){
           $category->child=$category->childs;


        }
        return response()->json(['data' =>$allCategories ,'message'=>'result returned successfully', 'status'=>200]);
    } catch(Exception $e) {
        return response()->json(['message' => 'something went error','status'=>401]);
      }
    }

/**
 *  function of childCategories that return specific child of category
 *
*/
      public function childCategories($id)
      {
          try{
          $categorey = Category::findOrFail($id);
           $child=$categorey->childs;
         return response()->json(['data' => $child,'message'=>'result returned successfully', 'status'=>200]);
        } catch(Exception $e) {
            return response()->json(['message' => 'something went error','status'=>401]);
          }
        }


        /**
 *function of addArticles insert Article in database after validation
 *
 */
    public function addArticle(ArticleStoreRequest $request)
    {
        try{
        $request->validated();


        Article::create($request->all());
        return response()->json(['message' => 'Article created successfully','status'=>200]);
    } catch(Exception $e) {
        return response()->json(['message' => 'something went error','status'=>401]);
      }
    }

        /**
 *function of findCateogryArticles  retrieve category with its child articles
 *
 */

 public function findCateogryArticles($category_id){

    $articles=Category::getAllArticles($category_id);
          return response()->json(['data' =>$articles ,'status'=>200]);

 }

/**update category */
 public function updateCategory(Request $request ,$id){
     try{
        $category=Category::findOrFail($id);

            $category->title=$request->title;
            $category->parent_id=$request->parent_id;
           $category->save();
        } catch(Exception $e) {
            return response()->json(['message' => 'something went error','status'=>401]);
          }
 }


    /**update Article */
    public function updateArticle(Request $request ,$id){
        try{
        $article=Article::findOrFail($id);

            $article->title=$request->title;
            $article->category_id=$request->category_id;
        $article->save();
    } catch(Exception $e) {
        return response()->json(['message' => 'something went error','status'=>401]);
      }
    }
}

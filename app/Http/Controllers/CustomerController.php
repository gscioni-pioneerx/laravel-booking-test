<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest; // Import the custom request class for customer validation
use App\Repository\CustomerRepository; // Import the repository for customer data management
use Illuminate\Http\JsonResponse; // Import the JsonResponse class for proper response handling

class CustomerController extends Controller
{
    // Declare a protected property to hold the injected CustomerRepository instance
    protected CustomerRepository $repo;

    /**
     * Constructor to inject the CustomerRepository dependency
     *
     * @param  \App\Repository\CustomerRepository  $repo
     */
    public function __construct(CustomerRepository $repo)
    {
        // Initialize the repository with the provided instance
        $this->repo = $repo;
    }

    /**
     * Display a listing of customers.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        // Retrieve all customers through the repository and return them as a JSON response
        return response()->json($this->repo->all());
    }

    /**
     * Store a newly created customer in the database.
     *
     * @param  \App\Http\Requests\StoreCustomerRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        // Create a new customer record using the validated request data
        $customer = $this->repo->create($request->validated());

        // Return the newly created customer data with a 201 status code (created)
        return response()->json($customer, 201);
    }
}

<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: proto/user.proto

namespace User;

/**
 * Protobuf type <code>User.UserService</code>
 */
interface UserServiceInterface
{
    /**
     * Method <code>signUp</code>
     *
     * @param \User\SignUpRequest $request
     * @return \User\SignUpResponse
     */
    public function signUp(\User\SignUpRequest $request);

}

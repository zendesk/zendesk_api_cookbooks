# app.rb

require 'rubygems'
require 'sinatra'
require 'sinatra/base'
require 'data_mapper'

require 'dotenv'
Dotenv.load

class MyApp < Sinatra::Base
	DataMapper.setup(:default, ENV['DATABASE_URL'] || "postgres://localhost/incremental")

	class Average
		include DataMapper::Resource

		property :id, Serial
		property :wait_time, String
	  property :reply_time, String
	  property :resolution_time, String
	end

	class Ticket
	  include DataMapper::Resource

	  property :id, Serial
	  property :ticket_id, String
	  property :wait_time, String
	  property :reply_time, String
	  property :resolution_time, String
	end

	DataMapper.finalize.auto_upgrade!

	get '/' do
		erb :index
	end

	get '/wait_time' do
		@wait_time = Average.last(:wait_time)

    render json: @wait_time
  end

  get '/reply_time' do
		@reply_time = Average.last(:reply_time)

    render json: @reply_time
  end

  get '/resolution_time' do
		@resolution_time = Average.last params(:resolution_time)

    render json: @resolution_time
  end
end

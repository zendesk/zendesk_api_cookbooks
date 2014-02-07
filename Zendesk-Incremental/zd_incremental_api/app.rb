# app.rb

require 'rubygems'
require 'sinatra'
require 'sinatra/base'
require 'data_mapper'
require './bin/update_metrics'

require 'dotenv'
Dotenv.load

class MyApp < Sinatra::Base
	DataMapper.setup(:default, ENV['DATABASE_URL'] || "postgres://localhost/incremental")

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
    # compute averages based on field and store in averages table
    @wait_time = Ticket.avg(:wait_time)

    render json: @wait_time
  end

  get '/reply_time' do
		@reply_time = Ticket.avg(:reply_time)

    render json: @reply_time
  end

  get '/resolution_time' do
		@resolution_time = Ticket.avg(:resolution_time)

    render json: @resolution_time
  end
end
